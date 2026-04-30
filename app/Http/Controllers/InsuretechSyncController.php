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
        return response()->json($result, 200);
    }
}
