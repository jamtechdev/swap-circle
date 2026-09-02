<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('system_settings')->updateOrInsert(
            ['type' => 'claim_waiting_days'],
            ['description' => '30']
        );
    }

    public function down(): void
    {
        DB::table('system_settings')->where('type', 'claim_waiting_days')->delete();
    }
};
