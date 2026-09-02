<?php

namespace App\Providers;

use App\View\Composers\AuthViewComposer;
use Illuminate\Support\Facades\View;
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
        View::composer([
            'layout.auth.master',
            'admin.login',
            'users.users_customers_login',
            'users.users_customers_signup',
            'users.users_customers_signup_individual',
            'users.users_customers_signup_corporate',
            'users.users_customers_forgot_password',
            'users.users_customers_reset_password',
            'users.users_customers_verification_code',
            'users.users_customers_resend_otp',
            'users.users_customers_signup_verified',
            'users.users_customers_signup_wait',
        ], AuthViewComposer::class);
    }
}
