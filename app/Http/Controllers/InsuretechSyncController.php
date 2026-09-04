<?php

namespace App\Http\Controllers;

use App\services\InsuretechSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class InsuretechSyncController extends Controller
{
    /**
     * Unified InsureTech sync:
     * - Batch (default): verify + pull products + push mapped purchases (optional limit, product_id).
     * - Single purchase: products_purchases_id.
     * - Inline sale (API): customer_name + customer_email (+ optional kyc, etc.).
     */
    public function sync(Request $request, InsuretechSyncService $syncService)
    {
        $purchaseId = (int) $request->input('products_purchases_id', 0);

        if ($purchaseId > 0) {
            $request->validate([
                'products_purchases_id' => 'required|integer|min:1',
            ]);

            // Admin/manual retry: clear prior success lock so sync can run again
            if ($request->boolean('force_retry') && Schema::hasTable('products_purchases')) {
                $clear = [];
                if (Schema::hasColumn('products_purchases', 'insuretech_synced_at')) {
                      $clear['insuretech_synced_at'] = null;
                }
                if (Schema::hasColumn('products_purchases', 'insuretech_sync_status')) {
                    $clear['insuretech_sync_status'] = 'pending_retry';
                }
                if (Schema::hasColumn('products_purchases', 'insuretech_sync_error')) {
                    $clear['insuretech_sync_error'] = null;
                }
                if ($clear !== []) {
                    DB::table('products_purchases')->where('products_purchases_id', $purchaseId)->update($clear);
                }
            }
        } else {
            $customerName = trim((string) $request->input('customer_name', ''));
            $customerEmail = trim((string) $request->input('customer_email', ''));
            $isInlineSale = $customerName !== '' || $customerEmail !== '';

            if ($isInlineSale) {
                $request->validate([
                    'customer_name' => 'required|string|max:255',
                    'customer_email' => 'required|email|max:255',
                    'phone' => 'nullable|string|max:30',
                    'cover_duration' => 'nullable|string|max:100',
                    'transaction_number' => 'nullable|string|max:100',
                    'product_code' => 'nullable|string|max:80',
                    'status' => 'nullable|in:active,suspended,pending,cancelled,failed',
                    'notes' => 'nullable|string|max:1000',
                    'amount' => 'nullable|numeric|min:0',
                    'currency' => 'nullable|string|size:3',
                    'kyc' => 'nullable|array',
                ]);
            } else {
                $request->validate([
                    'limit' => 'nullable|integer|min:1|max:500',
                    'product_id' => 'nullable|integer|min:1',
                ]);
            }
        }

        $result = $syncService->runSync($request->all());

        if ($purchaseId > 0 && Schema::hasTable('products_purchases')) {
            $ok = (bool) ($result['ok'] ?? false);
            $syncUpdate = [];
            if (Schema::hasColumn('products_purchases', 'insuretech_sync_status')) {
                $syncUpdate['insuretech_sync_status'] = $ok ? 'success' : 'failed';
            }
            if ($ok && Schema::hasColumn('products_purchases', 'insuretech_synced_at')) {
                $syncUpdate['insuretech_synced_at'] = now();
            }
            if (!$ok && Schema::hasColumn('products_purchases', 'insuretech_sync_error')) {
                $syncUpdate['insuretech_sync_error'] = Str::limit((string) ($result['message'] ?? 'Sync failed'), 1000);
            }
            if ($ok && Schema::hasColumn('products_purchases', 'insuretech_sync_error')) {
                $syncUpdate['insuretech_sync_error'] = null;
            }
            if ($syncUpdate !== []) {
                DB::table('products_purchases')->where('products_purchases_id', $purchaseId)->update($syncUpdate);
            }
        }

        return response()->json($result, ($result['ok'] ?? false) ? 200 : 422);
    }
}
