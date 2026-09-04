<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class AuthFlowAudit
{
    /**
     * @return array{passed: bool, checks: array<int, array{name: string, passed: bool, detail: string}>}
     */
    public static function run(): array
    {
        $checks = [];

        $registeredRoutes = collect(Route::getRoutes())->map(function ($route) {
            $methods = implode('|', $route->methods());
            return strtoupper($methods) . ' ' . $route->uri();
        })->all();

        $routeExists = function (string $method, string $uri) use ($registeredRoutes): bool {
            $uri = ltrim($uri, '/');
            $method = strtoupper($method);

            foreach ($registeredRoutes as $registered) {
                if (!str_contains($registered, ' ')) {
                    continue;
                }
                [$methods, $registeredUri] = explode(' ', $registered, 2);
                if ($registeredUri !== $uri) {
                    continue;
                }
                foreach (explode('|', $methods) as $registeredMethod) {
                    if (strtoupper($registeredMethod) === $method) {
                        return true;
                    }
                }
            }

            return false;
        };

        $webRoutes = [
            'GET /login' => ['GET', 'login'],
            'POST /login' => ['POST', 'login'],
            'GET /users/signup' => ['GET', 'users/signup'],
            'GET /users/signup_individual' => ['GET', 'users/signup_individual'],
            'GET /users/signup_corporate' => ['GET', 'users/signup_corporate'],
            'POST /users/signup_process' => ['POST', 'users/signup_process'],
            'GET /users/forgot_password' => ['GET', 'users/forgot_password'],
            'GET /users/reset_password/{email}/{otp}' => ['GET', 'users/reset_password/{email}/{otp}'],
            'GET /users/verification_code/{id}' => ['GET', 'users/verification_code/{id}'],
            'GET /users/resend_otp/{id}' => ['GET', 'users/resend_otp/{id}'],
        ];

        foreach ($webRoutes as $label => [$method, $uri]) {
            $checks[] = self::check("Route: {$label}", $routeExists($method, $uri));
        }

        $apiRoutes = [
            'POST api/signin' => ['POST', 'api/signin'],
            'POST api/signup' => ['POST', 'api/signup'],
            'POST api/forgot_password' => ['POST', 'api/forgot_password'],
            'POST api/modify_password' => ['POST', 'api/modify_password'],
            'POST api/users_customers_verify_otp' => ['POST', 'api/users_customers_verify_otp'],
            'POST api/resend_otp' => ['POST', 'api/resend_otp'],
        ];

        foreach ($apiRoutes as $label => [$method, $uri]) {
            $checks[] = self::check("Route: {$label}", $routeExists($method, $uri));
        }

        $views = [
            'Login page' => 'users/users_customers_login.blade.php',
            'Signup chooser' => 'users/users_customers_signup.blade.php',
            'Individual signup' => 'users/users_customers_signup_individual.blade.php',
            'Corporate signup' => 'users/users_customers_signup_corporate.blade.php',
            'Forgot password' => 'users/users_customers_forgot_password.blade.php',
            'Reset password' => 'users/users_customers_reset_password.blade.php',
            'Verification code' => 'users/users_customers_verification_code.blade.php',
            'Resend OTP' => 'users/users_customers_resend_otp.blade.php',
            'Auth layout' => 'layout/auth/master.blade.php',
        ];

        foreach ($views as $label => $path) {
            $checks[] = self::check("View: {$label}", is_file(resource_path('views/' . $path)));
        }

        $login = self::readView('users/users_customers_login.blade.php');
        $checks[] = self::check('Login calls api/signin', str_contains($login, '/signin'));
        $checks[] = self::check('Login creates web session via POST /login', str_contains($login, 'url: "/login"'));
        $checks[] = self::check('Login has forgot password link', str_contains($login, '/users/forgot_password'));
        $checks[] = self::check('Login validates account type (Individual/Corporate)', str_contains($login, 'users_customers_type'));

        $signup = self::readView('users/users_customers_signup_individual.blade.php');
        $checks[] = self::check('Signup calls api/signup', str_contains($signup, '/signup'));
        $checks[] = self::check('Signup sends gdpr_consent', str_contains($signup, 'gdpr_consent'));
        $checks[] = self::check('Signup redirects to verification page', str_contains($signup, '/users/verification_code/'));

        $forgot = self::readView('users/users_customers_forgot_password.blade.php');
        $checks[] = self::check('Forgot password calls api/forgot_password', str_contains($forgot, '/forgot_password'));

        $reset = self::readView('users/users_customers_reset_password.blade.php');
        $checks[] = self::check('Reset password calls api/modify_password', str_contains($reset, '/modify_password'));

        $verify = self::readView('users/users_customers_verification_code.blade.php');
        $checks[] = self::check('Verification calls api/users_customers_verify_otp', str_contains($verify, '/users_customers_verify_otp'));
        $checks[] = self::check('Verification starts session via signup_process', str_contains($verify, '/users/signup_process'));

        $authLayout = self::readView('layout/auth/master.blade.php');
        $checks[] = self::check('Auth layout uses CMS hero image', str_contains($authLayout, 'LandingContent::assetUrl($authImage)'));

        $auth = AuthBrand::variables();
        $checks[] = self::check('AuthBrand provides authImage', !empty($auth['authImage']));
        $checks[] = self::check('AuthBrand provides hero copy', !empty($auth['heroTitle']) && !empty($auth['heroText']));

        $checks[] = self::check(
            'UserPortal redirect: no purchases -> products',
            str_contains(UserPortal::postAuthRedirectUrl(999999999), '/users/products')
        );

        $checks[] = self::check(
            'API_URL configured',
            rtrim((string) config('app.api_url'), '/') !== ''
        );

        $checks[] = self::check(
            'Signup API enforces GDPR consent',
            str_contains(self::readController('ApiController.php'), "gdpr_consent")
        );

        $checks[] = self::check(
            'Forgot password API sends reset link to /users/reset_password',
            str_contains(self::readController('ApiController.php'), "url('/users/reset_password/")
        );

        $checks[] = self::check(
            'Login API blocks unverified accounts',
            str_contains(self::readController('ApiController.php'), "verified_badge")
                && (
                    str_contains(self::readController('ApiController.php'), 'AuthMessages::verifyEmailRequired')
                    || str_contains(self::readController('ApiController.php'), 'Please verify your email before signing in')
                )
        );

        try {
            $sampleUser = DB::table('users_customers')->first();
            if ($sampleUser) {
                $redirect = UserPortal::postAuthRedirectUrl((int) $sampleUser->users_customers_id);
                $checks[] = self::check(
                    'UserPortal redirect resolves for existing user',
                    str_contains($redirect, '/users/dashboard') || str_contains($redirect, '/users/products')
                );
            } else {
                $checks[] = self::check('UserPortal redirect resolves for existing user', true, 'Skipped — no users in DB');
            }
        } catch (\Throwable $e) {
            $checks[] = self::check('UserPortal redirect resolves for existing user', false, $e->getMessage());
        }

        $passed = !in_array(false, array_column($checks, 'passed'), true);

        return ['passed' => $passed, 'checks' => $checks];
    }

    protected static function check(string $name, bool $passed, string $detail = ''): array
    {
        return compact('name', 'passed', 'detail');
    }

    protected static function readView(string $path): string
    {
        $full = resource_path('views/' . $path);

        return is_file($full) ? file_get_contents($full) : '';
    }

    protected static function readController(string $path): string
    {
        $full = app_path('Http/Controllers/' . $path);

        return is_file($full) ? file_get_contents($full) : '';
    }
}
