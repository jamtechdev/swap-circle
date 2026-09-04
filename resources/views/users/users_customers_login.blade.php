@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Sign In')

@php
    $heroTitle = 'Welcome back.<br>Pick up where you left off.';
    $heroText = 'Sign in to manage products, purchases, and stay connected with your community.';
@endphp

@section('content')
    <div class="text-center lg:text-left">
        <img src="{{ asset('uploads/system_image/' . $brandLogo) }}" alt="{{ $brandName }}" class="mx-auto h-12 w-auto lg:mx-0">
        <p class="mt-6 text-sm font-semibold text-lime-hover">Hi</p>
        <h1 class="mt-1 text-3xl font-bold text-forest sm:text-4xl">Welcome Back!</h1>
        <p class="mt-2 text-sm text-gray-500">Sign in to your {{ $brandName }} account.</p>
    </div>

    <div class="mt-8">
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

    <p class="mt-8 text-center text-sm text-gray-500 lg:text-left">
        New to the community?
        <a href="{{ url('/users/signup_individual') }}" class="font-bold text-forest hover:text-lime-hover">Get started here</a>
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

    function showSignInError(response, fallback) {
        var message = (response && response.message) ? response.message : (fallback || "Unable to sign in. Please try again.");

        if (response && response.verification_required) {
            var resendUrl = response.resend_url || (response.users_customers_id
                ? "{{ url('/users/resend_otp') }}/" + response.users_customers_id
                : "{{ url('/users/signup_individual') }}");
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

    function sessionLogin(payload) {
        $.ajax({
            url: "/login",
            method: "POST",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(Object.assign({ _token: "{{ csrf_token() }}" }, payload)),
            success: function (response) {
                if (response && response.success) {
                    window.location.href = response.redirect_url || "/users/dashboard";
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
                sessionLogin({
                    users_customers_type: response.data.users_customers_type,
                    users_customers_id: response.data.users_customers_id,
                    profile_pic: response.data.profile_pic,
                    first_name: response.data.first_name,
                    last_name: response.data.last_name,
                    email: response.data.email,
                    phone: response.data.phone,
                });
            },
            error: function (xhr) {
                handleSignInAjaxError(xhr);
            }
        });
    });
});
</script>
@endpush
