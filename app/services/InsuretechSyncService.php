<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class InsuretechSyncService
{
    private function httpClient()
    {
        $baseUrl = rtrim((string) config('insuretech.admin_base_url'), '/');
        $token = (string) config('insuretech.partner_token');
        $timeout = (int) config('insuretech.request_timeout_seconds', 20);

        if ($baseUrl === '' || $token === '') {
            throw new \RuntimeException('INSURETECH_ADMIN_BASE_URL or INSURETECH_PARTNER_TOKEN is missing in swap-circle .env.');
        }

        return Http::baseUrl($baseUrl)
            ->timeout($timeout)
            ->acceptJson()
            ->withToken($token);
    }

    public function testAdminConnection(): array
    {
        try {
            $response = $this->httpClient()->get('/api/v1/partner/products');
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
        $synced = 0;

        foreach ($products as $product) {
            $name = (string) ($product['name'] ?? '');
            $code = (string) ($product['product_code'] ?? '');
            $uuid = (string) ($product['uuid'] ?? '');
            $resolvedPrice = (float) (
                $product['partner_price']
                ?? $product['effective_price']
                ?? $product['guide_price']
                ?? $product['base_price']
                ?? $product['price']
                ?? 0
            );
            $mapped = DB::table('it_product_mappings')
                ->where('admin_product_code', $code)
                ->first();
            if ($code === '') {
                continue;
            }

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
                    'admin_product_uuid' => $uuid !== '' ? $uuid : null,
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
                    'currency' => (string) ($product['partner_currency'] ?? 'NGN'),
                    'price' => $resolvedPrice,
                    'cover_duration_rule' => 'both',
                    'status' => strtolower((string) ($product['status'] ?? 'active')) === 'active' ? 'active' : 'inactive',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $synced++;
        }

        return [
            'ok' => true,
            'message' => 'Products pulled from admin portal.',
            'synced_products' => $synced,
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

        $transactionPayload = [
            'transaction_number' => (string) ($purchase->transaction_number ?: 'SWAP-TXN-'.$purchase->products_purchases_id),
            'customer_name' => $customerName !== '' ? $customerName : 'Customer Swap',
            'customer_email' => $customerEmail,
            'product_code' => $adminProductCode,
            'cover_duration' => (string) ($purchase->cover_duration ?? ''),
            'status' => $this->normalizePartnerTransactionStatus((string) ($purchase->payment_status ?? 'Pending')),
            'notes' => (string) ($purchase->payment_message ?? 'Synced from swap-circle'),
            'amount' => (float) ($product->price ?? 0),
            'currency' => 'NGN',
            'date_added' => $purchase->date_added ?? now()->toDateTimeString(),
        ];

        $transactionResponse = $this->httpClient()
            ->withHeaders(['Idempotency-Key' => (string) $transactionPayload['transaction_number']])
            ->post('/api/v1/transactions', $transactionPayload);
        if (! $transactionResponse->successful() && str_contains(strtolower((string) data_get($transactionResponse->json(), 'message', '')), 'product not found')) {
            // Auto-recover: create partner-scoped code and retry once.
            $adminProductCode = $this->ensureAdminProductForPartner($product, true);
            $transactionPayload['product_code'] = $adminProductCode;
            $transactionResponse = $this->httpClient()
                ->withHeaders(['Idempotency-Key' => (string) $transactionPayload['transaction_number']])
                ->post('/api/v1/transactions', $transactionPayload);
        }
        if (! $transactionResponse->successful()) {
            return [
                'ok' => false,
                'message' => 'Transaction push failed.',
                'details' => $transactionResponse->json(),
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

    private function ensureAdminProductForPartner(object $localProduct, bool $forceNewCode = false): string
    {
        $existingMapping = DB::table('it_product_mappings')
            ->where('local_product_id', $localProduct->products_id)
            ->first();

        if ($existingMapping && ! empty($existingMapping->admin_product_code)) {
            return (string) $existingMapping->admin_product_code;
        }

        throw new \RuntimeException(
            'No mapped admin product found. Ask admin to create/assign product, then run pull-products sync on Swap.'
        );
    }

    public function syncAllToAdmin(?int $limit = null, ?int $productId = null): array
    {
        $pullResult = $this->pullProductsFromAdmin();

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
            'message' => 'Bulk sync completed.',
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
