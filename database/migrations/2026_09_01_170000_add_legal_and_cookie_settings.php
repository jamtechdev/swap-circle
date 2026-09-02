<?php

use App\Support\LegalContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        LegalContent::seedDefaults();
    }

    public function down(): void
    {
        DB::table('system_settings')
            ->whereIn('type', array_keys(LegalContent::defaults()))
            ->delete();
    }
};
