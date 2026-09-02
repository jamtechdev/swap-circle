@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Verify Email')

@php
    $heroEyebrow = 'Almost there';
    $heroTitle = 'Check your inbox.<br>Enter your code.';
    $heroText = 'We sent a 4-digit verification code to your registered email. Enter it below to activate your account.';
@endphp

@section('back_url', url('/users/signup'))
@section('back_label', 'Back to signup')

@section('content')
    <div class="text-center lg:text-left">
        <img src="{{ asset('uploads/system_image/' . $brandLogo) }}" alt="{{ $brandName }}" class="mx-auto h-12 w-auto lg:mx-0">
        <div class="mx-auto mt-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-lime-soft lg:mx-0">
            <svg class="h-7 w-7 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h1 class="mt-5 font-display text-3xl font-bold text-forest">Verification Code</h1>
        <p class="mt-2 text-sm text-gray-500">Enter the 4-digit code sent to your registered email address.</p>
    </div>

    <form id="frm_verification_code" class="mt-8 space-y-6" novalidate>
        @csrf
        <input type="hidden" id="users_customers_id" value="{{ $id }}">

        <div>
            <label class="auth-label text-center lg:text-left">Verification code</label>
            <div class="flex justify-center gap-2 sm:gap-3 lg:justify-start" data-otp-group>
                <input type="text" class="auth-otp-input" name="digit_1" id="digit_1" maxlength="1" inputmode="numeric" autocomplete="one-time-code" aria-label="Digit 1">
                <input type="text" class="auth-otp-input" name="digit_2" id="digit_2" maxlength="1" inputmode="numeric" autocomplete="off" aria-label="Digit 2">
                <input type="text" class="auth-otp-input" name="digit_3" id="digit_3" maxlength="1" inputmode="numeric" autocomplete="off" aria-label="Digit 3">
                <input type="text" class="auth-otp-input" name="digit_4" id="digit_4" maxlength="1" inputmode="numeric" autocomplete="off" aria-label="Digit 4">
            </div>
            <span class="auth-error text-center lg:text-left" id="error_otp"></span>
        </div>

        <button type="submit" id="btn_verify" class="auth-btn-primary">
            <span id="btn_verify_text">Verify &amp; Continue</span>
            <span id="btn_verify_loader" class="hidden items-center gap-2">
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Verifying...
            </span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500 lg:text-left">
        Didn't receive the code?
        <a href="{{ url('/users/resend_otp/' . $id) }}" class="font-bold text-forest hover:text-lime-hover">Resend OTP</a>
    </p>
@endsection

@push('scripts')
<script>
$(function () {
    $("#frm_verification_code").validate({
        rules: {
            digit_1: { required: true },
            digit_2: { required: true },
            digit_3: { required: true },
            digit_4: { required: true },
        },
        messages: {
            digit_1: { required: "Enter the 4-digit verification code." },
            digit_2: { required: "Enter the 4-digit verification code." },
            digit_3: { required: "Enter the 4-digit verification code." },
            digit_4: { required: "Enter the 4-digit verification code." },
        },
        errorPlacement: function (error, element) {
            if (["digit_1","digit_2","digit_3","digit_4"].includes(element.attr("name"))) {
                $("#error_otp").html(error);
            }
        }
    });

    $("#frm_verification_code").on("submit", function (event) {
        event.preventDefault();
        if (!$("#frm_verification_code").valid()) return;

        var otp = $("#digit_1").val() + $("#digit_2").val() + $("#digit_3").val() + $("#digit_4").val();
        $("#btn_verify").prop("disabled", true);
        $("#btn_verify_text").addClass("hidden");
        $("#btn_verify_loader").removeClass("hidden").addClass("inline-flex");

        $.ajax({
            url: "{{ rtrim(config('app.api_url'), '/') }}/users_customers_verify_otp",
            method: "POST",
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify({
                users_customers_id: $("#users_customers_id").val(),
                verify_otp: otp,
            }),
        }).done(function (response) {
            if (response.status === "error") {
                toastr.error(response.message);
                return;
            }
            $.ajax({
                url: "/users/signup_process",
                method: "POST",
                headers: { "Content-Type": "application/json" },
                data: JSON.stringify({
                    _token: "{{ csrf_token() }}",
                    users_customers_type: response.data.users_customers_type,
                    users_customers_id: response.data.users_customers_id,
                    profile_pic: response.data.profile_pic,
                    first_name: response.data.first_name,
                    last_name: response.data.last_name,
                    company_name: response.data.company_name,
                    email: response.data.email,
                    phone: response.data.phone,
                }),
            }).done(function (sessionResponse) {
                if (sessionResponse && sessionResponse.success) {
                    window.location.href = sessionResponse.redirect_url || "/users/signup_verified";
                    return;
                }
                toastr.error((sessionResponse && sessionResponse.message) || "Verified, but we could not start your session. Please sign in.");
            }).fail(function () {
                toastr.error("Verified, but we could not start your session. Please sign in.");
            });
        }).fail(function () {
            toastr.error("Unable to verify code. Please try again.");
        }).always(function () {
            $("#btn_verify").prop("disabled", false);
            $("#btn_verify_text").removeClass("hidden");
            $("#btn_verify_loader").addClass("hidden").removeClass("inline-flex");
        });
    });
});
</script>
@endpush
