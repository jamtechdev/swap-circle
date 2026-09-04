<?php

namespace App\Providers;

use App\View\Composers\AuthViewComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Production: never follow public/hot (Vite HMR). Accidental deploy of that
        // file would blank auth CSS/JS by pointing browsers at localhost:5173.
        if ($this->app->environment('production')) {
            Vite::useHotFile(storage_path('framework/.vite-hot-disabled'));
        }

        View::composer([
            'layout.auth.master',
            'admin.login',
            'users.users_customers_login',
            'users.users_customers_signup',
            'users.users_customers_signup_individual',
            'users.users_customers_signup_corporate',
            'users.users_customers_signup_corporate_soon',
            'users.users_customers_forgot_password',
            'users.users_customers_reset_password',
            'users.users_customers_verification_code',
            'users.users_customers_resend_otp',
            'users.users_customers_signup_verified',
            'users.users_customers_signup_wait',
            'errors.404',
            'errors.500',
        ], AuthViewComposer::class);
    }
}
