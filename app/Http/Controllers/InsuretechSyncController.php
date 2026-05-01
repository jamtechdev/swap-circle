<?php

namespace App\Http\Controllers;

use App\services\InsuretechSyncService;
use Illuminate\Http\Request;

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

        return response()->json($result, ($result['ok'] ?? false) ? 200 : 422);
    }
}
