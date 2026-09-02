<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class LandingAuth
{
    public static function context(): array
    {
        if (!session()->has('id')) {
            return [
                'logged_in' => false,
                'display_name' => null,
                'login_url' => url('/login'),
                'signup_url' => url('/users/signup'),
                'dashboard_url' => url('/login'),
                'products_url' => url('/login'),
                'connect_url' => url('/login'),
                'profile_url' => url('/login'),
            ];
        }

        $portalHome = UserPortal::postAuthRedirectUrl((int) session('id'));

        return [
            'logged_in' => true,
            'display_name' => self::displayName(),
            'login_url' => $portalHome,
            'signup_url' => $portalHome,
            'dashboard_url' => $portalHome,
            'products_url' => url('/users/products'),
            'connect_url' => url('/users/connect'),
            'profile_url' => url('/users/profile'),
        ];
    }

    public static function displayName(): string
    {
        if (session('users_customers_type') === 'Company' && session('company_name')) {
            return trim((string) session('company_name'));
        }

        $name = trim(((string) session('first_name')) . ' ' . ((string) session('last_name')));

        if ($name !== '') {
            return $name;
        }

        return (string) (session('email') ?: 'My Account');
    }

    public static function ctaSignupOrDashboard(array $auth): string
    {
        return $auth['logged_in'] ? $auth['dashboard_url'] : $auth['signup_url'];
    }

    public static function ctaSignupOrProducts(array $auth): string
    {
        return $auth['logged_in'] ? $auth['products_url'] : $auth['signup_url'];
    }

    public static function ctaLoginOrProducts(array $auth): string
    {
        return $auth['logged_in'] ? $auth['products_url'] : $auth['login_url'];
    }

    public static function ctaConnect(array $auth): string
    {
        return $auth['logged_in'] ? $auth['connect_url'] : $auth['login_url'];
    }
}
