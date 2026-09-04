@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Reset Password')

@php
    $heroTitle = 'Create a new<br>secure password.';
    $heroText = 'Choose a strong password you haven\'t used before. You\'ll use it to sign in to your Swap Circle account.';
@endphp

@section('back_url', url('/login'))
@section('back_label', 'Back to login')

@section('content')
    <div class="text-center lg:text-left">
        <img src="{{ asset('uploads/system_image/' . $brandLogo) }}" alt="{{ $brandName }}" class="mx-auto h-12 w-auto lg:mx-0">
        <div class="mx-auto mt-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-lime-soft lg:mx-0">
            <svg class="h-7 w-7 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h1 class="mt-5 text-3xl font-bold text-forest">Reset Password</h1>
        <p class="mt-2 text-sm text-gray-500">Create a new password for your {{ $brandName }} account.</p>
    </div>

    <form id="frm_reset_password" method="post" action="#" class="mt-8 space-y-4" novalidate>
        @csrf
        <input type="hidden" id="email" value="{{ $email }}">
        <input type="hidden" id="otp" value="{{ $otp }}">

        <div>
            <label for="password" class="auth-label">New password</label>
            <div class="relative">
                <span class="auth-input-icon">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </span>
                <input type="password" id="password" name="password" class="auth-input pr-11" placeholder="At least 7 characters" autocomplete="new-password">
                <button type="button" class="auth-input-toggle" data-toggle-password="#password" aria-label="Show password" aria-pressed="false"></button>
            </div>
            <span class="auth-error" id="error_password"></span>
        </div>

        <div>
            <label for="confirm_password" class="auth-label">Confirm new password</label>
            <div class="relative">
                <span class="auth-input-icon">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </span>
                <input type="password" id="confirm_password" name="confirm_password" class="auth-input pr-11" placeholder="Re-enter password" autocomplete="new-password">
                <button type="button" class="auth-input-toggle" data-toggle-password="#confirm_password" data-show-label="Show confirm password" data-hide-label="Hide confirm password" aria-label="Show confirm password" aria-pressed="false"></button>
            </div>
            <span class="auth-error" id="error_confirm_password"></span>
        </div>

        <button type="submit" id="btnResetPassword" class="auth-btn-primary mt-2">
            <span id="btn_reset_text">Reset Password</span>
            <span id="btn_reset_loader" class="hidden items-center gap-2">
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Resetting...
            </span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500 lg:text-left">
        Remember your password?
        <a href="{{ url('/login') }}" class="font-bold text-forest hover:text-lime-hover">Sign In</a>
    </p>
@endsection

@push('scripts')
<script>
$(function () {
    $("#frm_reset_password").validate({
        rules: {
            password: { required: true, minlength: 7 },
            confirm_password: { required: true, equalTo: "#password" },
        },
        messages: {
            password: {
                required: "This field is required.",
                minlength: "Password should be at least 7 characters long.",
            },
            confirm_password: {
                required: "This field is required.",
                equalTo: "Please enter the same value as password.",
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") === "password") $("#error_password").html(error);
            else if (element.attr("name") === "confirm_password") $("#error_confirm_password").html(error);
        }
    });

    $("#frm_reset_password").on("submit", function (event) {
        event.preventDefault();
        if (!$("#frm_reset_password").valid()) return;

        $("#btnResetPassword").prop("disabled", true);
        $("#btn_reset_text").addClass("hidden");
        $("#btn_reset_loader").removeClass("hidden").addClass("inline-flex");

        $.ajax({
            url: "{{ rtrim(config('app.api_url'), '/') }}/modify_password",
            method: "POST",
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify({
                email: $("#email").val(),
                otp: $("#otp").val(),
                password: $("#password").val(),
                confirm_password: $("#confirm_password").val(),
            }),
        }).done(function (response) {
            if (response.status === "error") {
                toastr.error(response.message);
            } else {
                toastr.success("Password reset successfully. Please sign in.");
                setTimeout(function () {
                    window.location.href = "/login";
                }, 1200);
            }
        }).fail(function () {
            toastr.error("Unable to reset password. Please try again.");
        }).always(function () {
            $("#btnResetPassword").prop("disabled", false);
            $("#btn_reset_text").removeClass("hidden");
            $("#btn_reset_loader").addClass("hidden").removeClass("inline-flex");
        });
    });
});
</script>
@endpush
