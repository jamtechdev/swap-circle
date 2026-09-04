<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ensure the Nigerian Community Beneficiary product exists for portal QA.
     * Matches age-limit detection by product name in buy form + ApiController.
     */
    public function up(): void
    {
        $name = 'Nigerian Community Beneficiary';
        $now = date('Y-m-d H:i:s');

        $existing = DB::table('products')
            ->whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->first();

        $payload = [
            'type' => 'A',
            'name' => $name,
            'description' => 'Community beneficiary cover for Nigerian residents. Annual or monthly cover with beneficiary details required at purchase.',
            'product_information' => 'Age of beneficiary must be between 18 and 65 years.',
            'price' => 100.00,
            'custom_price' => 100.00,
            'currency_code' => 'EUR',
            'currency_symbol' => '€',
            'status' => 'Active',
            'date_modified' => $now,
        ];

        if ($existing) {
            DB::table('products')
                ->where('products_id', $existing->products_id)
                ->update($payload);
            return;
        }

        $payload['image'] = '';
        $payload['date_added'] = $now;
        $payload['delivery_request_limit'] = 0;

        DB::table('products')->insert($payload);
    }

    public function down(): void
    {
        // Keep product data — do not delete purchases tied to this catalog item.
    }
};
