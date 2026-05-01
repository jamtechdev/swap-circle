<?php

namespace App\Http\Controllers;

use App\services\InsuretechSyncService;
use Illuminate\Http\Request;

class InsuretechSyncController extends Controller
{
    public function testConnection(InsuretechSyncService $syncService)
    {
        $result = $syncService->testAdminConnection();
        return response()->json($result, $result['ok'] ? 200 : 422);
    }

    public function pullProducts(InsuretechSyncService $syncService)
    {
        $result = $syncService->pullProductsFromAdmin();
        return response()->json($result, $result['ok'] ? 200 : 422);
    }

    public function pushPurchase(Request $request, InsuretechSyncService $syncService)
    {
        $request->validate([
            'products_purchases_id' => 'required|integer',
        ]);

        $result = $syncService->pushPurchaseToAdmin((int) $request->products_purchases_id);
        return response()->json($result, $result['ok'] ? 200 : 422);
    }

    public function syncedProducts()
    {
        $products = \DB::table('it_product_mappings')
            ->orderByDesc('last_synced_at')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $products,
        ]);
    }

    public function syncAll(InsuretechSyncService $syncService)
    {
        request()->validate([
            'limit' => 'nullable|integer|min:1|max:500',
            'product_id' => 'nullable|integer|min:1',
        ]);

        $limit = request()->integer('limit');
        $productId = request()->integer('product_id');
        $result = $syncService->syncAllToAdmin(
            $limit > 0 ? $limit : null,
            $productId > 0 ? $productId : null
        );
        return response()->json($result, ($result['ok'] ?? false) ? 200 : 422);
    }

    public function oneClickSale(Request $request, InsuretechSyncService $syncService)
    {
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

        $result = $syncService->lowCodeSaleSync($request->all());

        return response()->json($result, ($result['ok'] ?? false) ? 200 : 422);
    }
}
