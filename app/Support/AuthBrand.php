<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class AuthBrand
{
    public static function variables(): array
    {
        $brandName = config('app.name', 'Swap Circle');
        $brandLogo = 'logo.png';

        $auth = LandingContent::defaults()['auth'];
        try {
            $auth = array_replace_recursive($auth, LandingContent::all()['auth'] ?? []);
        } catch (\Throwable $e) {
            //
        }

        $authImage = preg_replace('#^public/#', '', (string) ($auth['image'] ?? ''));
        if ($authImage === '') {
            $authImage = 'users/assets/images/Rocket_Boy_Flatline.png';
        }

        $heroEyebrow = (string) ($auth['eyebrow'] ?? 'Community Exchange Platform');
        $heroTitle = (string) ($auth['title'] ?? 'Trade services.<br>Build connections.');
        $heroText = (string) ($auth['text'] ?? 'Swap Circle brings people together to exchange skills, services, and opportunities inside one trusted community.');

        try {
            $brandName = optional(DB::table('system_settings')->where('type', 'system_name')->first())->description ?: $brandName;
            $brandLogo = optional(DB::table('system_settings')->where('type', 'system_image')->first())->description ?: $brandLogo;
        } catch (\Throwable $e) {
        }

        return compact('brandName', 'brandLogo', 'heroEyebrow', 'heroTitle', 'heroText', 'authImage');
    }
}
