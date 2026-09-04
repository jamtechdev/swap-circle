<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fund_wallets') && !Schema::hasColumn('fund_wallets', 'admin_note')) {
            Schema::table('fund_wallets', function (Blueprint $table) {
                $table->text('admin_note')->nullable()->after('status');
                $table->unsignedBigInteger('processed_by_admin_id')->nullable()->after('admin_note');
                $table->timestamp('processed_at')->nullable()->after('processed_by_admin_id');
            });
        }

        if (Schema::hasTable('withdraw_wallets_requests') && !Schema::hasColumn('withdraw_wallets_requests', 'admin_note')) {
            Schema::table('withdraw_wallets_requests', function (Blueprint $table) {
                $table->text('admin_note')->nullable()->after('status');
                $table->unsignedBigInteger('processed_by_admin_id')->nullable()->after('admin_note');
                $table->timestamp('processed_at')->nullable()->after('processed_by_admin_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('fund_wallets') && Schema::hasColumn('fund_wallets', 'admin_note')) {
            Schema::table('fund_wallets', function (Blueprint $table) {
                $table->dropColumn(['admin_note', 'processed_by_admin_id', 'processed_at']);
            });
        }
        if (Schema::hasTable('withdraw_wallets_requests') && Schema::hasColumn('withdraw_wallets_requests', 'admin_note')) {
            Schema::table('withdraw_wallets_requests', function (Blueprint $table) {
                $table->dropColumn(['admin_note', 'processed_by_admin_id', 'processed_at']);
            });
        }
    }
};
