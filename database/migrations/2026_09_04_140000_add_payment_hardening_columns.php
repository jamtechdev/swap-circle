<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('stripe_webhook_events')) {
            Schema::create('stripe_webhook_events', function (Blueprint $table) {
                $table->id();
                $table->string('event_id', 255)->unique();
                $table->string('event_type', 120)->nullable();
                $table->unsignedBigInteger('products_purchases_id')->nullable()->index();
                $table->string('processing_status', 32)->default('processed');
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('products_purchases')) {
            Schema::table('products_purchases', function (Blueprint $table) {
                if (!Schema::hasColumn('products_purchases', 'confirmation_sent_at')) {
                    $table->timestamp('confirmation_sent_at')->nullable()->after('payment_message');
                }
                if (!Schema::hasColumn('products_purchases', 'insuretech_synced_at')) {
                    $table->timestamp('insuretech_synced_at')->nullable()->after('confirmation_sent_at');
                }
                if (!Schema::hasColumn('products_purchases', 'insuretech_sync_status')) {
                    $table->string('insuretech_sync_status', 32)->nullable()->after('insuretech_synced_at');
                }
                if (!Schema::hasColumn('products_purchases', 'insuretech_sync_error')) {
                    $table->text('insuretech_sync_error')->nullable()->after('insuretech_sync_status');
                }
                if (!Schema::hasColumn('products_purchases', 'stripe_refund_id')) {
                    $table->string('stripe_refund_id', 255)->nullable()->after('stripe_payment_intent');
                }
                if (!Schema::hasColumn('products_purchases', 'refunded_at')) {
                    $table->timestamp('refunded_at')->nullable()->after('stripe_refund_id');
                }
                if (!Schema::hasColumn('products_purchases', 'payment_finalized_source')) {
                    $table->string('payment_finalized_source', 80)->nullable()->after('payment_message');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_webhook_events');

        if (Schema::hasTable('products_purchases')) {
            Schema::table('products_purchases', function (Blueprint $table) {
                foreach ([
                    'confirmation_sent_at',
                    'insuretech_synced_at',
                    'insuretech_sync_status',
                    'insuretech_sync_error',
                    'stripe_refund_id',
                    'refunded_at',
                    'payment_finalized_source',
                ] as $col) {
                    if (Schema::hasColumn('products_purchases', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
