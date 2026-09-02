<?php

namespace App\Http\Controllers;

use App\Support\LandingAuth;
use App\Support\LandingContent;
use App\Support\LandingInsights;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        $brand = [
            'name' => config('app.name', 'Swap Circle'),
            'logo' => 'logo.png',
        ];

        try {
            $brand['name'] = optional(
                DB::table('system_settings')->where('type', 'system_name')->first()
            )->description ?: $brand['name'];

            $brand['logo'] = optional(
                DB::table('system_settings')->where('type', 'system_image')->first()
            )->description ?: $brand['logo'];
        } catch (\Throwable $e) {
            // Use defaults when DB is unavailable.
        }

        $auth = LandingAuth::context();
        $content = LandingContent::all();
        $insightPosts = LandingInsights::posts($auth, $content);

        return view('landing.index', compact('brand', 'auth', 'content', 'insightPosts'));
    }
}
