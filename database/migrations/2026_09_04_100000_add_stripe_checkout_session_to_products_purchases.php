<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products_purchases', function (Blueprint $table) {
            if (!Schema::hasColumn('products_purchases', 'stripe_checkout_session_id')) {
                $table->string('stripe_checkout_session_id', 255)->nullable()->after('stripe_payment_intent');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products_purchases', function (Blueprint $table) {
            if (Schema::hasColumn('products_purchases', 'stripe_checkout_session_id')) {
                $table->dropColumn('stripe_checkout_session_id');
            }
        });
    }
};
