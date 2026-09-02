<?php

namespace App\Http\Controllers;

use App\Support\LandingAuth;
use App\Support\LegalContent;
use Illuminate\Support\Facades\DB;

class LegalController extends Controller
{
    public function privacy()
    {
        return $this->page(
            'Privacy Policy',
            'How we collect, store, and protect your personal information.',
            LegalContent::get('privacy_text'),
            'privacy'
        );
    }

    public function terms()
    {
        return $this->page(
            'Terms of Service',
            'The rules and guidelines for using the Swap Circle platform.',
            LegalContent::get('terms_text'),
            'terms'
        );
    }

    public function cookies()
    {
        return $this->page(
            'Cookie Policy',
            'How we use cookies and how you can manage your preferences.',
            LegalContent::get('cookie_text'),
            'cookies'
        );
    }

    public function gdpr()
    {
        return $this->page(
            'GDPR & Data Protection',
            'Your data protection rights and how we comply with GDPR.',
            LegalContent::get('gdpr_text'),
            'gdpr'
        );
    }

    protected function page(string $title, string $subtitle, string $body, string $slug)
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
            //
        }

        $body = $this->formatBody($body);
        $auth = LandingAuth::context();

        return view('legal.show', compact('title', 'subtitle', 'body', 'slug', 'brand', 'auth'));
    }

    protected function formatBody(string $body): string
    {
        if (trim(strip_tags($body)) === '') {
            return '<p>This page is being updated. Please contact <a href="mailto:support@swapcircle.trade">support@swapcircle.trade</a> for assistance.</p>';
        }

        // Plain-text admin content (e.g. lorem ipsum) — wrap into readable paragraphs.
        if (strip_tags($body) === $body) {
            $chunks = preg_split('/\R\s*\R/', trim($body)) ?: [trim($body)];

            return implode('', array_map(function ($chunk) {
                $chunk = trim($chunk);
                if ($chunk === '') {
                    return '';
                }

                return '<p>' . nl2br(e($chunk)) . '</p>';
            }, $chunks));
        }

        return $body;
    }
}
