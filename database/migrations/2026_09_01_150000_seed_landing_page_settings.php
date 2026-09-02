<?php

use App\Support\LandingContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (LandingContent::defaults() as $section => $data) {
            DB::table('system_settings')->updateOrInsert(
                ['type' => 'landing_' . $section],
                ['description' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]
            );
        }
    }

    public function down(): void
    {
        DB::table('system_settings')
            ->whereIn('type', collect(LandingContent::sections())->map(fn ($s) => 'landing_' . $s)->all())
            ->delete();
    }
};
