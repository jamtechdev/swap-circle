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
            // Fallback to .env/config when system_settings table is unavailable.
        }

        if ($envKey !== null) {
            return trim((string) env($envKey, $default));
        }

        return $default;
    }

    private function httpClient()
    {
        $baseUrl = rtrim($this->getRuntimeSetting('insuretech_admin_base_url', 'INSURETECH_ADMIN_BASE_URL', (string) config('insuretech.admin_base_url')), '/');
        $token = $this->getRuntimeSetting('insuretech_partner_token', 'INSURETECH_PARTNER_TOKEN', (string) config('insuretech.partner_token'));
        $timeout = (int) $this->getRuntimeSetting('insuretech_request_timeout', 'INSURETECH_REQUEST_TIMEOUT', (string) config('insuretech.request_timeout_seconds', 20));

        if ($baseUrl === '' || $token === '') {
            throw new \RuntimeException('Insuretech settings missing. Add partner settings in admin panel or .env.');
        }

        return Http::baseUrl($baseUrl)
            ->timeout($timeout)
            ->acceptJson()
            ->withToken($token);
    }

    public function testAdminConnection(): array
    {
        try {
            $response = $this->httpClient()->get('/api/v1/verify-token');
        } catch (\Throwable $exception) {
            return [
                'ok' => false,
                'message' => 'Unable to reach admin portal.',
                'error' => $exception->getMessage(),
            ];
        }

        if (! $response->successful()) {
            return [
                'ok' => false,
                'message' => 'Admin portal responded with an error.',
                'status' => $response->status(),
                'details' => $response->json(),
            ];
        }

        return [
            'ok' => true,
            'message' => 'Connection to admin portal is healthy.',
            'status' => $response->status(),
        ];
    }

    public function pullProductsFromAdmin(): array
    {
        $response = $this->httpClient()->get('/api/v1/partner/products');
        if (! $response->successful()) {
            return [
                'ok' => false,
                'message' => 'Failed to fetch products from admin portal.',
                'details' => $response->json(),
            ];
        }

        $products = (array) data_get($response->json(), 'data', []);
        $incomingCodes = [];
        $synced = 0;
        $deactivated = 0;

        foreach ($products as $product) {
            $name = (string) ($product['name'] ?? '');
            $code = (string) ($product['product_code'] ?? '');
            $resolvedPrice = (float) ($product['price'] ?? 0);
            $mapped = DB::table('it_product_mappings')
                ->where('admin_product_code', $code)
                ->first();
            if ($code === '') {
                continue;
            }
            $incomingCodes[] = $code;

            $localProduct = null;
            if ($mapped && ! empty($mapped->local_product_id)) {
                $localProduct = DB::table('products')
                    ->where('products_id', (int) $mapped->local_product_id)
                    ->first();
            }

            if (! $localProduct && $name !== '') {
                $localProduct = DB::table('products')
                    ->where('name', $name)
                    ->first();
            }

            if (! $localProduct) {
                $newProductId = DB::table('products')->insertGetId([
                    'name' => $name !== '' ? $name : ('Product '.$code),
                    'description' => (string) ($product['description'] ?? ''),
                    'price' => $resolvedPrice,
                    'type' => 'A',
                    'status' => strtolower((string) ($product['status'] ?? 'active')) === 'active' ? 'Active' : 'Inactive',
                    'image' => (string) ($product['image_url'] ?? ''),
                    'date_added' => now()->toDateTimeString(),
                    'date_modified' => now()->toDateTimeString(),
                ]);

                $localProduct = DB::table('products')
                    ->where('products_id', $newProductId)
                    ->first();
            }

            DB::table('it_product_mappings')->updateOrInsert(
                ['admin_product_code' => $code],
                [
                    'local_product_id' => $localProduct->products_id ?? null,
                    'admin_product_uuid' => null,
                    'admin_product_name' => $name !== '' ? $name : null,
                    'last_synced_at' => now(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            if ($localProduct && isset($localProduct->products_id)) {
                DB::table('products')
                    ->where('products_id', $localProduct->products_id)
                    ->update([
                        'name' => $name !== '' ? $name : (string) ($localProduct->name ?? ''),
                        'description' => (string) ($product['description'] ?? ($localProduct->description ?? '')),
                        'price' => $resolvedPrice > 0 ? $resolvedPrice : (float) ($localProduct->price ?? 0),
                        'status' => strtolower((string) ($product['status'] ?? 'active')) === 'active' ? 'Active' : 'Inactive',
                        'image' => (string) ($product['image_url'] ?? ($localProduct->image ?? '')),
                        'date_modified' => now()->toDateTimeString(),
                    ]);
            }

            DB::table('it_products')->updateOrInsert(
                ['product_code' => $code],
                [
                    'name' => $name !== '' ? $name : 'Product '.$code,
                    'slug' => Str::slug($name !== '' ? $name : $code),
                    'description' => (string) ($product['description'] ?? ''),
                    'currency' => 'NGN',
                    'price' => $resolvedPrice,
                    'cover_duration_rule' => 'both',
                    'status' => strtolower((string) ($product['status'] ?? 'active')) === 'active' ? 'active' : 'inactive',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $synced++;
        }

        $incomingCodes = array_values(array_unique($incomingCodes));
        if (! empty($incomingCodes)) {
            $activeLocalIds = DB::table('it_product_mappings')
                ->whereIn('admin_product_code', $incomingCodes)
                ->whereNotNull('local_product_id')
                ->pluck('local_product_id')
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
                ->all();

            $staleLocalIds = DB::table('it_product_mappings')
                ->whereNotIn('admin_product_code', $incomingCodes)
                ->whereNotNull('local_product_id')
                ->pluck('local_product_id')
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
                ->all();

            $localIdsToDeactivate = array_values(array_diff($staleLocalIds, $activeLocalIds));

            if (! empty($localIdsToDeactivate)) {
                $deactivated = DB::table('products')
                    ->whereIn('products_id', $localIdsToDeactivate)
                    ->where('status', '!=', 'Inactive')
                    ->update([
                        'status' => 'Inactive',
                        'date_modified' => now()->toDateTimeString(),
                    ]);
            }
        }

        return [
            'ok' => true,
            'message' => 'Products pulled from admin portal.',
            'synced_products' => $synced,
            'deactivated_products' => $deactivated,
        ];
    }

    public function pushPurchaseToAdmin(int $productsPurchasesId): array
    {
        if ((bool) config('insuretech.auto_pull_before_push', true)) {
            // Keep Swap product catalog fresh before attempting customer/transaction push.
            $this->pullProductsFromAdmin();
        }

        $purchase = DB::table('products_purchases')->where('products_purchases_id', $productsPurchasesId)->first();
        if (! $purchase) {
            return ['ok' => false, 'message' => 'Purchase not found.'];
        }

        $customer = DB::table('users_customers')->where('users_customers_id', $purchase->users_customers_id)->first();
        $product = DB::table('products')->where('products_id', $purchase->products_id)->first();
        if (! $customer || ! $product) {
            return ['ok' => false, 'message' => 'Customer or product missing for purchase sync.'];
        }

        $customerEmail = (string) ($customer->email ?? '');
        if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $customerEmail = 'swapuser_'.$customer->users_customers_id.'@swapcircle.local';
        }

        $adminProductCode = $this->ensureAdminProductForPartner($product);

        $customerName = trim((string) (($customer->first_name ?? 'Customer').' '.($customer->last_name ?? 'Swap')));
        $transactionNumber = (string) ($purchase->transaction_number ?: 'SWAP-TXN-'.$purchase->products_purchases_id);
        $coverDuration = $this->resolveCoverDuration((string) ($purchase->cover_duration ?? ''));

        $submitPayload = [
            'transaction_number' => $transactionNumber,
            'customer_name' => $customerName !== '' ? $customerName : 'Customer Swap',
            'customer_email' => $customerEmail,
            'phone' => (string) ($customer->phone ?? ''),
            'cover_duration' => $coverDuration,
            'status' => $this->normalizePartnerTransactionStatus((string) ($purchase->payment_status ?? 'Pending')),
            'notes' => (string) ($purchase->payment_message ?? 'Synced from swap-circle'),
            'amount' => (float) ($product->price ?? 0),
            'currency' => 'NGN',
            'kyc' => [
                'id_type' => (string) ($customer->id_type ?? ''),
                'id_number' => (string) ($customer->id_number ?? ''),
            ],
        ];

        $submitResponse = $this->httpClient()
            ->withHeaders(['Idempotency-Key' => $transactionNumber])
            ->post("/api/v1/products/{$adminProductCode}/submit", $submitPayload);

        if (! $submitResponse->successful()) {
            return [
                'ok' => false,
                'message' => 'Policy submission failed.',
                'details' => $submitResponse->json(),
            ];
        }

        // Push KYC again in dedicated endpoint for consistency.
        $kycResponse = $this->httpClient()->post(
            "/api/v1/products/{$adminProductCode}/transactions/{$transactionNumber}/kyc",
            ['kyc' => $submitPayload['kyc']]
        );

        if (! $kycResponse->successful()) {
            return [
                'ok' => false,
                'message' => 'Policy submitted but KYC push failed.',
                'details' => $kycResponse->json(),
            ];
        }

        return [
            'ok' => true,
            'message' => 'Purchase synced to admin portal.',
            'purchase_id' => $productsPurchasesId,
            'admin_product_code' => $adminProductCode,
        ];
    }

    private function normalizePartnerTransactionStatus(string $paymentStatus): string
    {
        $normalized = strtolower(trim($paymentStatus));

        return match ($normalized) {
            'successful', 'success', 'paid', 'completed', 'active' => 'active',
            'failed', 'cancelled', 'canceled', 'suspended' => 'suspended',
            default => 'pending',
        };
    }

    private function ensureAdminProductForPartner(object $localProduct): string
    {
        $existingMapping = DB::table('it_product_mappings')
            ->where('local_product_id', $localProduct->products_id)
            ->orderByDesc('last_synced_at')
            ->orderByDesc('id')
            ->first();

        if ($existingMapping && ! empty($existingMapping->admin_product_code)) {
            return (string) $existingMapping->admin_product_code;
        }

        throw new \RuntimeException(
            'No mapped admin product found. Ask admin to create/assign product, then run pull-products sync on Swap.'
        );
    }

    private function resolveCoverDuration(string $rawCoverDuration): string
    {
        $value = strtolower(trim($rawCoverDuration));

        if (str_contains($value, '365') || str_contains($value, 'annual') || str_contains($value, 'year')) {
            return '365_days';
        }
        if (str_contains($value, '90')) {
            return '90_days';
        }

        return '30_days';
    }

    private function resolveDefaultAdminProductCode(): string
    {
        $configured = trim((string) config('insuretech.default_admin_product_code', ''));
        if ($configured !== '') {
            return $configured;
        }

        $latest = DB::table('it_product_mappings')
            ->whereNotNull('admin_product_code')
            ->orderByDesc('last_synced_at')
            ->orderByDesc('id')
            ->value('admin_product_code');

        if (! empty($latest)) {
            return (string) $latest;
        }

        throw new \RuntimeException('No default admin product code found. Set INSURETECH_DEFAULT_PRODUCT_CODE or run sync-all first.');
    }

    public function lowCodeSaleSync(array $payload): array
    {
        $connection = $this->testAdminConnection();
        if (($connection['ok'] ?? false) !== true) {
            return [
                'ok' => false,
                'message' => 'InsureTech connection failed.',
                'connection' => $connection,
            ];
        }

        $pull = $this->pullProductsFromAdmin();
        if (($pull['ok'] ?? false) !== true) {
            return [
                'ok' => false,
                'message' => 'Could not refresh product mappings before sync.',
                'products_pull' => $pull,
            ];
        }

        $productCode = (string) ($payload['product_code'] ?? $this->resolveDefaultAdminProductCode());
        $transactionNumber = (string) ($payload['transaction_number'] ?? ('SWAP-LOWCODE-'.now()->timestamp.'-'.random_int(100, 999)));
        $coverDuration = $this->resolveCoverDuration((string) ($payload['cover_duration'] ?? '30_days'));

        $submitPayload = [
            'transaction_number' => $transactionNumber,
            'customer_name' => (string) ($payload['customer_name'] ?? 'Customer Swap'),
            'customer_email' => (string) ($payload['customer_email'] ?? 'customer@swapcircle.local'),
            'phone' => (string) ($payload['phone'] ?? ''),
            'cover_duration' => $coverDuration,
            'status' => (string) ($payload['status'] ?? 'pending'),
            'notes' => (string) ($payload['notes'] ?? 'Low-code auto sync from swap-circle'),
            'amount' => (float) ($payload['amount'] ?? 0),
            'currency' => (string) ($payload['currency'] ?? 'NGN'),
            'kyc' => is_array($payload['kyc'] ?? null) ? $payload['kyc'] : [],
        ];

        $submitResponse = $this->httpClient()
            ->withHeaders(['Idempotency-Key' => $transactionNumber])
            ->post("/api/v1/products/{$productCode}/submit", $submitPayload);

        if (! $submitResponse->successful()) {
            return [
                'ok' => false,
                'message' => 'Low-code submit failed.',
                'details' => $submitResponse->json(),
            ];
        }

        $kycResponse = $this->httpClient()->post(
            "/api/v1/products/{$productCode}/transactions/{$transactionNumber}/kyc",
            ['kyc' => $submitPayload['kyc']]
        );

        if (! $kycResponse->successful()) {
            return [
                'ok' => false,
                'message' => 'Policy submitted but KYC step failed.',
                'details' => $kycResponse->json(),
            ];
        }

        return [
            'ok' => true,
            'message' => 'Low-code sync completed with one call.',
            'transaction_number' => $transactionNumber,
            'product_code' => $productCode,
            'connection' => $connection,
            'products_pull' => $pull,
        ];
    }

    public function syncAllToAdmin(?int $limit = null, ?int $productId = null): array
    {
        $connectionResult = $this->testAdminConnection();
        if (($connectionResult['ok'] ?? false) !== true) {
            return [
                'ok' => false,
                'message' => 'Sync aborted: unable to verify InsureTech admin connection.',
                'connection' => $connectionResult,
            ];
        }

        $pullResult = $this->pullProductsFromAdmin();
        if (($pullResult['ok'] ?? false) !== true) {
            return [
                'ok' => false,
                'message' => 'Sync aborted: failed to pull products from InsureTech admin.',
                'connection' => $connectionResult,
                'products_pull' => $pullResult,
            ];
        }

        $purchaseQuery = DB::table('products_purchases')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('it_product_mappings')
                    ->whereColumn('it_product_mappings.local_product_id', 'products_purchases.products_id');
            })
            ->orderByDesc('products_purchases_id')
            ->select(['products_purchases_id']);

        if ($productId && $productId > 0) {
            $purchaseQuery->where('products_id', $productId);
        }

        $maxLimit = (int) config('insuretech.max_sync_limit', 200);
        $effectiveLimit = $limit ?: (int) config('insuretech.default_sync_limit', 25);
        $effectiveLimit = max(1, min($effectiveLimit, $maxLimit));

        if ($effectiveLimit > 0) {
            $purchaseQuery->limit($effectiveLimit);
        }

        $purchases = $purchaseQuery->get();

        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($purchases as $purchase) {
            $result = $this->pushPurchaseToAdmin((int) $purchase->products_purchases_id);
            if (($result['ok'] ?? false) === true) {
                $successCount++;
            } else {
                $failedCount++;
                $errors[] = [
                    'products_purchases_id' => (int) $purchase->products_purchases_id,
                    'message' => $result['message'] ?? 'Sync failed',
                ];
            }
        }

        return [
            'ok' => true,
            'message' => 'Centralized sync completed (connection + product pull + purchase push).',
            'connection' => $connectionResult,
            'products_pull' => $pullResult,
            'product_id_filter' => $productId,
            'limit_applied' => $effectiveLimit,
            'total_attempted' => $purchases->count(),
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'errors' => $errors,
        ];
    }
}
