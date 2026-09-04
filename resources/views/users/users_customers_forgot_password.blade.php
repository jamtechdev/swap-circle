@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Forgot Password')

@php
    $heroTitle = 'Forgot your password?<br>We\'ve got you.';
    $heroText = 'Enter the email linked to your account and we\'ll send you a secure link to reset your password.';
@endphp

@section('back_url', url('/login'))
@section('back_label', 'Back to login')

@section('content')
    <div class="text-center lg:text-left">
        <img src="{{ asset('uploads/system_image/' . $brandLogo) }}" alt="{{ $brandName }}" class="mx-auto h-12 w-auto lg:mx-0">
        <div class="mx-auto mt-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-lime-soft lg:mx-0">
            <svg class="h-7 w-7 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
        </div>
        <h1 class="mt-5 text-3xl font-bold text-forest">Forgot Password?</h1>
        <p class="mt-2 text-sm text-gray-500">Enter your registered email and we'll send a secure reset link.</p>
    </div>

    <form id="frm_forgot_password" class="mt-8 space-y-4" novalidate>
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

        <button type="submit" id="btnForgotPassword" class="auth-btn-primary mt-2">
            <span id="btn_forgot_text">Send Reset Link</span>
            <span id="btn_forgot_loader" class="hidden items-center gap-2">
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Sending...
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
    $("#frm_forgot_password").validate({
        rules: {
            email: { required: true, email: true },
        },
        messages: {
            email: {
                required: "This field is required.",
                email: "Please enter a valid email address.",
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") === "email") $("#error_email").html(error);
        }
    });

    $("#frm_forgot_password").on("submit", function (event) {
        event.preventDefault();
        if (!$("#frm_forgot_password").valid()) return;

        $("#btnForgotPassword").prop("disabled", true);
        $("#btn_forgot_text").addClass("hidden");
        $("#btn_forgot_loader").removeClass("hidden").addClass("inline-flex");

        $.ajax({
            url: "{{ rtrim(config('app.api_url'), '/') }}/forgot_password",
            method: "POST",
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify({ email: $("#email").val() }),
        }).done(function (response) {
            if (response.status === "error") {
                toastr.error(response.message);
            } else {
                toastr.success(response.data.message || "Password reset link has been sent to your email.");
                $("#frm_forgot_password")[0].reset();
            }
        }).fail(function () {
            toastr.error("Unable to send reset link. Please try again.");
        }).always(function () {
            $("#btnForgotPassword").prop("disabled", false);
            $("#btn_forgot_text").removeClass("hidden");
            $("#btn_forgot_loader").addClass("hidden").removeClass("inline-flex");
        });
    });
});
</script>
@endpush
