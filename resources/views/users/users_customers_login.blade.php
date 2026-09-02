@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Sign In')

@php
    $heroTitle = 'Welcome back.<br>Pick up where you left off.';
    $heroText = 'Sign in to manage wallets, products, swap offers, and stay connected with your community.';
@endphp

@section('content')
    <div class="text-center lg:text-left">
        <img src="{{ asset('uploads/system_image/' . $brandLogo) }}" alt="{{ $brandName }}" class="mx-auto h-12 w-auto lg:mx-0">
        <p class="mt-6 text-sm font-semibold text-lime-hover">Hi, 👋</p>
        <h1 class="mt-1 font-display text-3xl font-bold text-forest sm:text-4xl">Welcome Back!</h1>
        <p class="mt-2 text-sm text-gray-500">Sign in to your {{ $brandName }} account.</p>
    </div>

    <div class="mt-8" data-auth-tabs>
        <div class="mb-6 flex rounded-full bg-lime-soft p-1" role="tablist">
            <button type="button" data-auth-tab="individual" class="auth-tab auth-tab-active" aria-selected="true">Individual</button>
            <button type="button" data-auth-tab="corporate" class="auth-tab auth-tab-inactive" aria-selected="false">Corporate</button>
        </div>

        {{-- Individual login --}}
        <div data-auth-panel="individual">
            <form id="frm_login_individual" class="space-y-4" novalidate>
                @csrf
                <div>
                    <label for="email" class="auth-label">Email address</label>
                    <div class="relative">
                        <span class="auth-input-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <input type="email" id="email" name="email" class="auth-input" placeholder="you@email.com" autocomplete="email">
                    </div>
                    <span class="auth-error" id="error_email"></span>
                </div>
                <div>
                    <div class="mb-1.5 flex items-center justify-between">
                        <label for="password" class="auth-label mb-0">Password</label>
                        <a href="{{ url('/users/forgot_password') }}" class="text-xs font-semibold text-forest hover:text-lime-hover">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <span class="auth-input-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input type="password" id="password" name="password" class="auth-input pr-11" placeholder="Enter password" autocomplete="current-password">
                        <button type="button" class="auth-input-toggle" data-toggle-password="#password" aria-label="Show password" aria-pressed="false"></button>
                    </div>
                    <span class="auth-error" id="error_password"></span>
                </div>
                <button type="submit" class="auth-btn-primary mt-2">Sign In</button>
            </form>
        </div>

        {{-- Corporate login --}}
        <div data-auth-panel="corporate" class="hidden">
            <form id="frm_login_company" class="space-y-4" novalidate>
                @csrf
                <div>
                    <label for="company_email" class="auth-label">Corporate email</label>
                    <div class="relative">
                        <span class="auth-input-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </span>
                        <input type="email" id="company_email" name="company_email" class="auth-input" placeholder="company@email.com" autocomplete="email">
                    </div>
                    <span class="auth-error" id="error_company_email"></span>
                </div>
                <div>
                    <div class="mb-1.5 flex items-center justify-between">
                        <label for="company_password" class="auth-label mb-0">Password</label>
                        <a href="{{ url('/users/forgot_password') }}" class="text-xs font-semibold text-forest hover:text-lime-hover">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <span class="auth-input-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input type="password" id="company_password" name="company_password" class="auth-input pr-11" placeholder="Enter password" autocomplete="current-password">
                        <button type="button" class="auth-input-toggle" data-toggle-password="#company_password" aria-label="Show password" aria-pressed="false"></button>
                    </div>
                    <span class="auth-error" id="error_company_password"></span>
                </div>
                <button type="submit" class="auth-btn-primary mt-2">Sign In</button>
            </form>
        </div>
    </div>

    <p class="mt-8 text-center text-sm text-gray-500 lg:text-left">
        New to the community?
        <a href="{{ url('/users/signup') }}" class="font-bold text-forest hover:text-lime-hover">Get started here</a>
    </p>
@endsection

@push('scripts')
<script>
$(function () {
    $("#frm_login_individual").validate({
        rules: { email: { required: true, email: true }, password: { required: true } },
        messages: {
            email: { required: "Email is required.", email: "Enter a valid email." },
            password: { required: "Password is required." }
        },
        errorPlacement: function (error, element) {
            $("#error_" + element.attr("name")).html(error);
        }
    });

    $("#frm_login_company").validate({
        rules: { company_email: { required: true, email: true }, company_password: { required: true } },
        messages: {
            company_email: { required: "Email is required.", email: "Enter a valid email." },
            company_password: { required: "Password is required." }
        },
        errorPlacement: function (error, element) {
            $("#error_" + element.attr("name")).html(error);
        }
    });

    function showSignInError(response, fallback) {
        var message = (response && response.message) ? response.message : (fallback || "Unable to sign in. Please try again.");

        if (response && response.verification_required) {
            var resendUrl = response.resend_url || (response.users_customers_id
                ? "{{ url('/users/resend_otp') }}/" + response.users_customers_id
                : "{{ url('/users/signup') }}");
            message += '<br><a href="' + resendUrl + '">Resend verification code</a>';
            toastr.error(message, "", { escapeHtml: false, timeOut: 10000, extendedTimeOut: 5000 });
            return;
        }

        toastr.error(message);
    }

    function handleSignInAjaxError(xhr, fallback) {
        var message = fallback || "Unable to sign in. Please check your connection and try again.";
        if (xhr.responseJSON) {
            if (xhr.responseJSON.verification_required) {
                showSignInError(xhr.responseJSON, fallback);
                return;
            }
            if (xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
        }
        toastr.error(message);
    }

    function sessionLogin(payload, onSuccess) {
        $.ajax({
            url: "/login",
            method: "POST",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(Object.assign({ _token: "{{ csrf_token() }}" }, payload)),
            success: function (response) {
                if (response && response.success) {
                    window.location.href = response.redirect_url || "/users/dashboard";
                    if (typeof onSuccess === "function") {
                        onSuccess(response);
                    }
                    return;
                }
                toastr.error((response && response.message) || "Something went wrong. Please try again.");
            },
            error: function (xhr) {
                var message = "Unable to start your session. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            }
        });
    }

    $("#frm_login_individual").on("submit", function (e) {
        e.preventDefault();
        if (!$(this).valid()) return;
        $.ajax({
            url: "{{ rtrim(config('app.api_url'), '/') }}/signin",
            method: "POST",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify({ email: $("#email").val(), password: $("#password").val() }),
            success: function (response) {
                if (response.status === "error") return showSignInError(response);
                if (response.data.users_customers_type !== "Individual") return toastr.error("Please select Individual account type.");
                sessionLogin({
                    users_customers_type: response.data.users_customers_type,
                    users_customers_id: response.data.users_customers_id,
                    profile_pic: response.data.profile_pic,
                    first_name: response.data.first_name,
                    last_name: response.data.last_name,
                    email: response.data.email,
                    phone: response.data.phone,
                }, function () {});
            },
            error: function (xhr) {
                handleSignInAjaxError(xhr);
            }
        });
    });

    $("#frm_login_company").on("submit", function (e) {
        e.preventDefault();
        if (!$(this).valid()) return;
        $.ajax({
            url: "{{ rtrim(config('app.api_url'), '/') }}/signin",
            method: "POST",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify({ email: $("#company_email").val(), password: $("#company_password").val() }),
            success: function (response) {
                if (response.status === "error") return showSignInError(response);
                if (response.data.users_customers_type !== "Company") return toastr.error("Please select Corporate account type.");
                sessionLogin({
                    users_customers_type: response.data.users_customers_type,
                    users_customers_id: response.data.users_customers_id,
                    profile_pic: response.data.profile_pic,
                    company_name: response.data.company_name,
                    first_name: response.data.first_name,
                    email: response.data.email,
                    phone: response.data.phone,
                }, function () {});
            },
            error: function (xhr) {
                handleSignInAjaxError(xhr);
            }
        });
    });

});
</script>
@endpush
