<?php

use App\Support\LandingContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $auth = LandingContent::defaults()['auth'];

        $legacyImage = optional(DB::table('system_settings')->where('type', 'auth_image')->first())->description;
        if (!empty($legacyImage)) {
            $auth['image'] = preg_replace('#^public/#', '', $legacyImage);
        }

        DB::table('system_settings')->updateOrInsert(
            ['type' => 'landing_auth'],
            ['description' => json_encode($auth, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]
        );
    }

    public function down(): void
    {
        DB::table('system_settings')->where('type', 'landing_auth')->delete();
    }
};
