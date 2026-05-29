<?php

namespace App\services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class InsuretechSyncService
{
    private function getRuntimeSetting(string $type, ?string $envKey = null, string $default = ''): string
    {
        try {
            $row = DB::table('system_settings')->where('type', $type)->first();
            $value = trim((string) ($row->description ?? ''));
            if ($value !== '') {
                return $value;
            }
        } catch (\Throwable $exception) {
        }

        if ($envKey !== null) {
            return trim((string) env($envKey, $default));
        }

        return $default;
    }

    private function httpClient()
    {
        $baseUrl = rtrim($this->getRuntimeSetting('insuretech_admin_base_url', 'INSURETECH_ADMIN_BASE_URL', (string) config('insuretech.admin_base_url')), '/');
        $token   = $this->getRuntimeSetting('insuretech_partner_token', 'INSURETECH_PARTNER_TOKEN', (string) config('insuretech.partner_token'));
        $timeout = (int) $this->getRuntimeSetting('insuretech_request_timeout', 'INSURETECH_REQUEST_TIMEOUT', (string) config('insuretech.request_timeout_seconds', 20));

        if ($baseUrl === '' || $token === '') {
            throw new \RuntimeException('Insuretech settings missing. Add partner settings in admin panel or .env.');
        }

        return Http::baseUrl($baseUrl)->timeout($timeout)->acceptJson()->withToken($token);
    }

    public function testAdminConnection(): array
    {
        try {
            $response = $this->httpClient()->get('/api/v1/partner/products');
        } catch (\Throwable $exception) {
            return ['ok' => false, 'message' => 'Unable to reach admin portal.', 'error' => $exception->getMessage()];
        }

        if (! $response->successful()) {
            return ['ok' => false, 'message' => 'Admin portal responded with an error.', 'status' => $response->status(), 'details' => $response->json()];
        }

        return ['ok' => true, 'message' => 'Connection to admin portal is healthy.', 'status' => $response->status()];
    }

    public function pullProductsFromAdmin(): array
    {
        $response = $this->httpClient()->get('/api/v1/partner/products');
        if (! $response->successful()) {
            return ['ok' => false, 'message' => 'Failed to fetch products from admin portal.', 'details' => $response->json()];
        }

        $products      = (array) data_get($response->json(), 'data', []);
        $synced        = 0;

        foreach ($products as $product) {
            $name            = (string) ($product['name'] ?? '');
            $code            = (string) ($product['product_code'] ?? '');

            if ($code === '') continue;

            $mappedLocalId = DB::table('it_product_mappings')->where('admin_product_code', $code)->value('local_product_id');
            if (! $mappedLocalId && $name !== '') {
                $mappedLocalId = DB::table('products')->where('name', $name)->value('products_id');
            }

            DB::table('it_product_mappings')->updateOrInsert(
                ['admin_product_code' => $code],
                ['local_product_id' => $mappedLocalId ?: null, 'admin_product_uuid' => null, 'admin_product_name' => $name !== '' ? $name : null, 'last_synced_at' => now(), 'updated_at' => now(), 'created_at' => now()]
            );

            $synced++;
        }

        return ['ok' => true, 'message' => 'Product mappings refreshed; Swap product profiles were not changed.', 'synced_products' => $synced, 'deactivated_products' => 0];
    }

    public function pushPurchaseToAdmin(int $productsPurchasesId, bool $skipCatalogPull = false): array
    {
        if (! $skipCatalogPull && (bool) config('insuretech.auto_pull_before_push', false)) {
            $this->pullProductsFromAdmin();
        }

        $purchase = DB::table('products_purchases')->where('products_purchases_id', $productsPurchasesId)->first();
        if (! $purchase) {
            return ['ok' => false, 'message' => 'Purchase not found.'];
        }

        $customer = DB::table('users_customers')->where('users_customers_id', $purchase->users_customers_id)->first();
        $product  = DB::table('products')->where('products_id', $purchase->products_id)->first();
        if (! $customer || ! $product) {
            return ['ok' => false, 'message' => 'Customer or product missing for purchase sync.'];
        }

        $customerEmail = (string) ($customer->email ?? '');
        if (! filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $customerEmail = 'swapuser_' . $customer->users_customers_id . '@swapcircle.local';
        }

        $adminProductCode = $this->ensureAdminProductForPartner($product);
        $customerName     = trim((string) (($customer->first_name ?? 'Customer') . ' ' . ($customer->last_name ?? 'Swap')));
        $rawTxn           = (string) ($purchase->transaction_number ?: 'SWAP-TXN-' . $purchase->products_purchases_id);

        // Fixed per purchase — same every sync — admin portal does updateOrCreate on this
        $fixedTransactionNumber = 'SWAP-' . $purchase->products_purchases_id . '-' . md5($rawTxn);

        // Always new per attempt — prevents idempotency hash conflict on admin side
        $idempotencyKey = $fixedTransactionNumber . '-' . substr(md5(uniqid('', true)), 0, 8);

        $coverDuration = $this->resolveCoverDuration((string) ($purchase->cover_duration ?? ''));
        $partnerPrice  = $this->productSalePrice($product);
        $currencyCode  = $this->productCurrencyCode($product);

        $submitPayload = [
            'transaction_number' => $fixedTransactionNumber,
            'customer_name'      => $customerName !== '' ? $customerName : 'Customer Swap',
            'customer_email'     => $customerEmail,
            'phone'              => (string) ($customer->phone ?? ''),
            'cover_duration'     => $coverDuration,
            'status'             => $this->normalizePartnerTransactionStatus((string) ($purchase->payment_status ?? 'Pending')),
            'notes'              => (string) ($purchase->payment_message ?? 'Synced from swap-circle'),
            'amount'             => $partnerPrice,
            'currency'           => $currencyCode,
            'product'            => $this->productSnapshot($product, $adminProductCode),
            'date_added'         => (string) ($purchase->date_added ?? now()),
        ];

        $submitResponse = $this->httpClient()
            ->withHeaders(['Idempotency-Key' => $idempotencyKey])
            ->post("/api/v1/products/{$adminProductCode}/submit", $submitPayload);

        if (! $submitResponse->successful()) {
            return ['ok' => false, 'message' => 'Policy submission failed.', 'details' => $submitResponse->json()];
        }

        $savedTxn = data_get($submitResponse->json(), 'data.transaction_number', $fixedTransactionNumber);

        // KYC
        $beneficiary = DB::table('products_purchases_beneficiaries')->where('products_purchases_id', $productsPurchasesId)->first();

        $kycData = [
            'id_type'    => 'phone',
            'id_number'  => (string) ($beneficiary->phone_number ?? $customer->phone ?? ''),
            'first_name' => (string) ($beneficiary->first_name ?? $customer->first_name ?? ''),
            'last_name'  => (string) ($beneficiary->surname ?? $customer->last_name ?? ''),
            'dob'        => (string) ($beneficiary->date_of_birth ?? ''),
            'address'    => (string) ($beneficiary->address ?? ''),
        ];

        $kycResponse = $this->httpClient()->post(
            "/api/v1/products/{$adminProductCode}/transactions/{$savedTxn}/kyc",
            ['kyc' => $kycData]
        );

        if (! $kycResponse->successful()) {
            // Policy submitted — treat as success with warning
            return ['ok' => true, 'message' => 'Purchase synced. KYC push failed but policy submitted.', 'purchase_id' => $productsPurchasesId, 'admin_product_code' => $adminProductCode, 'kyc_error' => $kycResponse->json()];
        }

        return ['ok' => true, 'message' => 'Purchase synced to admin portal.', 'purchase_id' => $productsPurchasesId, 'admin_product_code' => $adminProductCode];
    }

    private function normalizePartnerTransactionStatus(string $paymentStatus): string
    {
        return match (strtolower(trim($paymentStatus))) {
            'successful', 'success', 'paid', 'completed', 'active' => 'active',
            'failed', 'cancelled', 'canceled', 'suspended'         => 'suspended',
            default                                                  => 'pending',
        };
    }

    private function ensureAdminProductForPartner(object $localProduct): string
    {
        $mapping = DB::table('it_product_mappings')->where('local_product_id', $localProduct->products_id)->orderByDesc('last_synced_at')->orderByDesc('id')->first();

        if ($mapping && ! empty($mapping->admin_product_code)) {
            return (string) $mapping->admin_product_code;
        }

        $productCode = $this->localProductCode($localProduct);

        DB::table('it_product_mappings')->updateOrInsert(
            ['admin_product_code' => $productCode],
            [
                'local_product_id' => $localProduct->products_id,
                'admin_product_uuid' => null,
                'admin_product_name' => (string) ($localProduct->name ?? ''),
                'last_synced_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return $productCode;
    }

    private function localProductCode(object $localProduct): string
    {
        try {
            if (DB::getSchemaBuilder()->hasColumn('products', 'product_code') && ! empty($localProduct->product_code)) {
                return strtoupper(Str::slug((string) $localProduct->product_code, '_'));
            }
        } catch (\Throwable $exception) {
        }

        return 'SWAP_PRODUCT_' . (int) $localProduct->products_id;
    }

    private function productSalePrice(object $product): float
    {
        foreach (['custom_price', 'price'] as $field) {
            if (isset($product->{$field}) && is_numeric($product->{$field})) {
                return (float) $product->{$field};
            }
        }

        return 0.0;
    }

    private function productCurrencyCode(object $product): string
    {
        $currency = strtoupper(trim((string) ($product->currency_code ?? 'NGN')));

        return preg_match('/^[A-Z]{3}$/', $currency) ? $currency : 'NGN';
    }

    private function productCurrencySymbol(object $product): string
    {
        return trim((string) ($product->currency_symbol ?? '₦')) ?: '₦';
    }

    private function productImageUrl(object $product): ?string
    {
        $image = trim((string) ($product->image ?? ''));
        if ($image === '') {
            return null;
        }

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        return url('/' . ltrim($image, '/'));
    }

    private function productSnapshot(object $product, string $adminProductCode): array
    {
        return [
            'product_code'    => $adminProductCode,
            'name'            => (string) ($product->name ?? ('Product ' . $adminProductCode)),
            'description'     => (string) ($product->description ?? ''),
            'price'           => $this->productSalePrice($product),
            'currency'        => $this->productCurrencyCode($product),
            'currency_symbol' => $this->productCurrencySymbol($product),
            'image_url'       => $this->productImageUrl($product),
            'status'          => strtolower((string) ($product->status ?? 'Active')) === 'active' ? 'active' : 'inactive',
        ];
    }

    private function resolveCoverDuration(string $rawCoverDuration): string
    {
        $value = strtolower(trim($rawCoverDuration));

        if (str_contains($value, '365') || str_contains($value, 'annual') || str_contains($value, 'year')) return '365_days';
        if (str_contains($value, '90')) return '90_days';

        return '30_days';
    }

    private function resolveDefaultAdminProductCode(): string
    {
        $configured = trim((string) config('insuretech.default_admin_product_code', ''));
        if ($configured !== '') return $configured;

        $latest = DB::table('it_product_mappings')->whereNotNull('admin_product_code')->orderByDesc('last_synced_at')->orderByDesc('id')->value('admin_product_code');
        if (! empty($latest)) return (string) $latest;

        throw new \RuntimeException('No default admin product code found.');
    }

    public function runSync(array $input): array
    {
        $purchaseId = (int) ($input['products_purchases_id'] ?? 0);

        if ($purchaseId > 0) {
            $connection = $this->testAdminConnection();
            if (! ($connection['ok'] ?? false)) return ['ok' => false, 'message' => 'InsureTech connection failed.', 'connection' => $connection];

            $push = $this->pushPurchaseToAdmin($purchaseId, true);
            $push['connection']    = $connection;
            $push['products_pull'] = ['ok' => true, 'message' => 'Skipped; Swap owns product profiling.'];
            $push['mode']          = 'single_purchase';

            return $push;
        }

        $customerName  = trim((string) ($input['customer_name'] ?? ''));
        $customerEmail = trim((string) ($input['customer_email'] ?? ''));
        if ($customerName !== '' && $customerEmail !== '' && filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $result         = $this->lowCodeSaleSync($input);
            $result['mode'] = 'inline_sale';
            return $result;
        }

        $limit     = isset($input['limit']) ? (int) $input['limit'] : null;
        $productId = isset($input['product_id']) ? (int) $input['product_id'] : null;

        $batch         = $this->syncAllToAdmin(($limit !== null && $limit > 0) ? $limit : null, ($productId !== null && $productId > 0) ? $productId : null);
        $batch['mode'] = 'batch';

        return $batch;
    }

    public function lowCodeSaleSync(array $payload): array
    {
        $connection = $this->testAdminConnection();
        if (! ($connection['ok'] ?? false)) return ['ok' => false, 'message' => 'InsureTech connection failed.', 'connection' => $connection];

        $productCode   = (string) ($payload['product_code'] ?? $this->resolveDefaultAdminProductCode());
        $txnNumber     = (string) ($payload['transaction_number'] ?? ('SWAP-LOWCODE-' . now()->timestamp . '-' . random_int(100, 999)));
        $coverDuration = $this->resolveCoverDuration((string) ($payload['cover_duration'] ?? '30_days'));

        $submitPayload = [
            'transaction_number' => $txnNumber,
            'customer_name'      => (string) ($payload['customer_name'] ?? 'Customer Swap'),
            'customer_email'     => (string) ($payload['customer_email'] ?? 'customer@swapcircle.local'),
            'phone'              => (string) ($payload['phone'] ?? ''),
            'cover_duration'     => $coverDuration,
            'status'             => (string) ($payload['status'] ?? 'pending'),
            'notes'              => (string) ($payload['notes'] ?? 'Low-code auto sync from swap-circle'),
            'amount'             => (float) ($payload['amount'] ?? 0),
            'currency'           => (string) ($payload['currency'] ?? 'NGN'),
            'kyc'                => is_array($payload['kyc'] ?? null) ? $payload['kyc'] : [],
        ];

        $submitResponse = $this->httpClient()->withHeaders(['Idempotency-Key' => $txnNumber])->post("/api/v1/products/{$productCode}/submit", $submitPayload);

        if (! $submitResponse->successful()) return ['ok' => false, 'message' => 'Low-code submit failed.', 'details' => $submitResponse->json()];

        $kycResponse = $this->httpClient()->post("/api/v1/products/{$productCode}/transactions/{$txnNumber}/kyc", ['kyc' => $submitPayload['kyc']]);

        if (! $kycResponse->successful()) return ['ok' => false, 'message' => 'Policy submitted but KYC step failed.', 'details' => $kycResponse->json()];

        return ['ok' => true, 'message' => 'Low-code sync completed.', 'transaction_number' => $txnNumber, 'product_code' => $productCode, 'connection' => $connection, 'products_pull' => ['ok' => true, 'message' => 'Skipped; Swap owns product profiling.']];
    }

    public function syncAllToAdmin(?int $limit = null, ?int $productId = null): array
    {
        $connectionResult = $this->testAdminConnection();
        if (! ($connectionResult['ok'] ?? false)) return ['ok' => false, 'message' => 'Sync aborted: unable to verify InsureTech admin connection.', 'connection' => $connectionResult];

        $pullResult = ['ok' => true, 'message' => 'Skipped; Swap owns product profiling.'];

        $purchaseQuery = DB::table('products_purchases')
            ->orderByDesc('products_purchases_id')
            ->select(['products_purchases_id']);

        if ($productId && $productId > 0) $purchaseQuery->where('products_id', $productId);

        $maxLimit      = (int) config('insuretech.max_sync_limit', 200);
        $effectiveLimit = max(1, min($limit ?: (int) config('insuretech.default_sync_limit', 25), $maxLimit));
        $purchaseQuery->limit($effectiveLimit);

        $purchases    = $purchaseQuery->get();
        $successCount = 0;
        $failedCount  = 0;
        $errors       = [];

        foreach ($purchases as $purchase) {
            $result = $this->pushPurchaseToAdmin((int) $purchase->products_purchases_id, true);
            if (($result['ok'] ?? false) === true) {
                $successCount++;
            } else {
                $failedCount++;
                $errors[] = ['products_purchases_id' => (int) $purchase->products_purchases_id, 'message' => $result['message'] ?? 'Sync failed', 'details' => $result['details'] ?? null];
            }
        }

        return ['ok' => true, 'message' => 'Sale sync completed (connection + purchase push).', 'connection' => $connectionResult, 'products_pull' => $pullResult, 'product_id_filter' => $productId, 'limit_applied' => $effectiveLimit, 'total_attempted' => $purchases->count(), 'success_count' => $successCount, 'failed_count' => $failedCount, 'errors' => $errors];
    }
}
