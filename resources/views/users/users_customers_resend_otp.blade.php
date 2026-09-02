@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Resend OTP')

@php
    $heroTitle = 'Didn\'t get the code?<br>Request a new one.';
    $heroText = 'We can send a fresh verification code to your registered email. Check your spam folder if you still don\'t see it.';
@endphp

@section('back_url', url('/users/verification_code/' . ($users_customers_id ?? '')))
@section('back_label', 'Back to verification')

@section('content')
    <div class="text-center lg:text-left">
        <img src="{{ asset('uploads/system_image/' . $brandLogo) }}" alt="{{ $brandName }}" class="mx-auto h-12 w-auto lg:mx-0">
        <div class="mx-auto mt-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-lime-soft lg:mx-0">
            <svg class="h-7 w-7 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <h1 class="mt-5 font-display text-3xl font-bold text-forest">Resend OTP</h1>
        <p class="mt-2 text-sm text-gray-500">Request a new verification code to be sent to your email.</p>
    </div>

    <form id="frm_resend_otp" class="mt-8 space-y-4" novalidate>
        @csrf
        <input type="hidden" name="users_customers_id" id="users_customers_id" value="{{ $users_customers_id ?? '' }}">

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

        <button type="submit" id="btn_resend" class="auth-btn-primary mt-2">
            <span id="btn_resend_text">Resend OTP</span>
            <span id="btn_resend_loader" class="hidden items-center gap-2">
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Sending...
            </span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500 lg:text-left">
        <a href="{{ url('/users/signup') }}" class="font-bold text-forest hover:text-lime-hover">← Back to Signup</a>
    </p>
@endsection

@push('scripts')
<script>
$(function () {
    $("#frm_resend_otp").validate({
        rules: {
            email: { required: true, email: true },
        },
        messages: {
            email: {
                required: "Please enter your email address.",
                email: "Please enter a valid email address.",
            },
        },
        errorPlacement: function (error, element) {
            $("#error_" + element.attr("name")).html(error);
        },
        submitHandler: function () {
            $("#btn_resend").prop("disabled", true);
            $("#btn_resend_text").addClass("hidden");
            $("#btn_resend_loader").removeClass("hidden").addClass("inline-flex");

            $.ajax({
                url: "{{ rtrim(config('app.api_url'), '/') }}/resend_otp",
                method: "POST",
                headers: { "Content-Type": "application/json" },
                data: JSON.stringify({
                    users_customers_id: $("#users_customers_id").val(),
                }),
            }).done(function (response) {
                if (response.status === "success") {
                    toastr.success(response.message + " New code sent to " + (response.method_used || "your email"));
                    setTimeout(function () {
                        window.location.href = "/users/verification_code/" + $("#users_customers_id").val();
                    }, 2000);
                } else {
                    toastr.error(response.message || "Failed to resend OTP");
                }
            }).fail(function () {
                toastr.error("Network error. Please try again.");
            }).always(function () {
                $("#btn_resend").prop("disabled", false);
                $("#btn_resend_text").removeClass("hidden");
                $("#btn_resend_loader").addClass("hidden").removeClass("inline-flex");
            });

            return false;
        },
    });
});
</script>
@endpush
