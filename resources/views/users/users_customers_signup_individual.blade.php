@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Individual Sign Up')
@section('back_url', url('/login'))
@section('back_label', 'Back to sign in')

@php
    $heroTitle = 'Create your<br>individual account.';
    $heroText = 'Join thousands of members buying products, managing cover, and protecting loved ones across borders.';
@endphp

@section('content_width')
    max-w-3xl
@endsection

@section('content')
    <div class="text-center lg:text-left">
        <img src="{{ asset('uploads/system_image/' . $brandLogo) }}" alt="{{ $brandName }}" class="mx-auto h-10 w-auto lg:mx-0">
        <h1 class="mt-4 text-2xl font-bold text-forest sm:text-3xl">Individual registration</h1>
        <p class="mt-1 text-sm text-gray-500">Fill in your details below. Fields marked with * are required.</p>
    </div>

    <form id="frm_signup" method="post" action="#" class="mt-8 space-y-8" novalidate>
        @csrf

        {{-- Profile photo (SC-08: optional) --}}
        <div>
            <p class="auth-section-title">Profile photo <span class="normal-case tracking-normal text-gray-400">(optional)</span></p>
            <label for="profile_pic" class="auth-upload group" id="upload_profile">
                <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" id="profile_pic_preview" alt="" class="h-10 w-10 opacity-60 group-hover:opacity-100">
                <span class="mt-2 text-xs font-semibold text-forest/70">Upload photo <span class="font-normal text-gray-400">(optional)</span></span>
                <input type="file" accept="image/png,image/jpg,image/jpeg" name="profile_pic" id="profile_pic" class="absolute inset-0 cursor-pointer opacity-0">
            </label>
            <span class="auth-error text-center" id="error_profile_pic"></span>
            <textarea id="profile_pic_string" hidden></textarea>
        </div>

        {{-- Personal info --}}
        <div>
            <p class="auth-section-title">Personal information</p>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="first_name" class="auth-label">First name *</label>
                    <input type="text" id="first_name" name="first_name" class="auth-input !pl-4" placeholder="John" autocomplete="given-name">
                    <span class="auth-error" id="error_first_name"></span>
                </div>
                <div>
                    <label for="sur_name" class="auth-label">Surname *</label>
                    <input type="text" id="sur_name" name="sur_name" class="auth-input !pl-4" placeholder="Doe" autocomplete="family-name">
                    <span class="auth-error" id="error_sur_name"></span>
                </div>
                <div>
                    <label for="phone_number" class="auth-label">Phone number *</label>
                    <div class="auth-phone-wrap">
                        <input type="tel" id="phone_number" name="phone_number" class="auth-phone-input" placeholder="801 234 5678" autocomplete="tel" inputmode="tel">
                    </div>
                    <p class="auth-phone-hint">Choose your country from the flag list, then enter your mobile number. International numbers are supported.</p>
                    <span class="auth-error" id="error_phone_number"></span>
                </div>
                <div>
                    <label for="email" class="auth-label">Email address *</label>
                    <input type="email" id="email" name="email" class="auth-input !pl-4" placeholder="you@email.com" autocomplete="email">
                    <span class="auth-error" id="error_email"></span>
                </div>
            </div>
        </div>

        {{-- Security --}}
        <div>
            <p class="auth-section-title">Security</p>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="auth-label">Create password *</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="auth-input !pl-4 pr-11" placeholder="Min. 7 characters" autocomplete="new-password">
                        <button type="button" class="auth-input-toggle" data-toggle-password="#password" aria-label="Show password" aria-pressed="false"></button>
                    </div>
                    <span class="auth-error" id="error_password"></span>
                </div>
                <div>
                    <label for="confirm_password" class="auth-label">Confirm password *</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" class="auth-input !pl-4 pr-11" placeholder="Repeat password" autocomplete="new-password">
                        <button type="button" class="auth-input-toggle" data-toggle-password="#confirm_password" data-show-label="Show confirm password" data-hide-label="Hide confirm password" aria-label="Show confirm password" aria-pressed="false"></button>
                    </div>
                    <span class="auth-error" id="error_confirm_password"></span>
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div>
            <p class="auth-section-title">Address</p>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="street" class="auth-label">Street address *</label>
                    <input type="text" id="street" name="street" class="auth-input !pl-4" placeholder="123 Main Street" autocomplete="street-address">
                    <span class="auth-error" id="error_street"></span>
                </div>
                <div>
                    <label for="city_state" class="auth-label">City / State *</label>
                    <input type="text" id="city_state" name="city_state" class="auth-input !pl-4" placeholder="Lagos" autocomplete="address-level2">
                    <span class="auth-error" id="error_city_state"></span>
                </div>
                <div>
                    <label for="country" class="auth-label">Country *</label>
                    <input type="text" id="country" name="country" class="auth-input !pl-4" placeholder="Nigeria" autocomplete="country-name">
                    <span class="auth-error" id="error_country"></span>
                </div>
            </div>
        </div>

        <div class="auth-consent">
            <input type="checkbox" id="gdpr_consent" name="gdpr_consent" value="1">
            <label for="gdpr_consent">
                I agree to the <a href="{{ url('/terms') }}" target="_blank" rel="noopener">Terms of Service</a>,
                <a href="{{ url('/privacy') }}" target="_blank" rel="noopener">Privacy Policy</a>, and confirm I have read the
                <a href="{{ url('/gdpr') }}" target="_blank" rel="noopener">GDPR &amp; Data Protection</a> information.
            </label>
        </div>
        <span class="auth-error" id="error_gdpr_consent"></span>

        <button type="submit" class="auth-btn-primary" id="btn_signup">
            <span id="btn_signup_text">Continue to verification</span>
            <span id="btn_signup_loader" class="hidden items-center gap-2">
                <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Please wait...
            </span>
        </button>
    </form>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('users/assets/plugin/select-flag/css/intlTelInput.min.css') }}">
<style>
/* SC-01 phone layout — do not force padding-left; intl-tel-input sets it for the dial code */
.auth-phone-wrap { width: 100%; }
.auth-phone-wrap .iti { width: 100%; display: block; font-family: "DM Sans", sans-serif; }
.auth-phone-wrap .iti__flag-container { z-index: 3; }
.auth-phone-wrap .iti__selected-flag {
    padding: 0 8px 0 10px;
    border-radius: 0.75rem 0 0 0.75rem;
}
.auth-phone-wrap .iti--separate-dial-code .iti__selected-dial-code {
    margin-left: 6px;
    color: #1a472a;
    font-size: 0.875rem;
    font-weight: 600;
}
.auth-phone-wrap .auth-phone-input {
    width: 100%;
    height: auto;
    box-sizing: border-box;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    background: #fff;
    padding-top: 0.875rem;
    padding-bottom: 0.875rem;
    padding-right: 1rem;
    /* left padding is applied by intl-tel-input for flag + dial code */
    font-size: 0.875rem;
    color: #1f2937;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    outline: none;
}
.auth-phone-wrap .auth-phone-input:focus {
    border-color: #1a472a;
    box-shadow: 0 0 0 3px rgba(200, 230, 53, 0.3);
}
.auth-phone-wrap .auth-phone-input::placeholder { color: #9ca3af; }
.auth-phone-hint { margin-top: 0.25rem; font-size: 0.75rem; line-height: 1.25rem; color: #9ca3af; }
</style>
@endpush

@push('scripts')
<script src="{{ asset('users/assets/js/jquery.additional.methods.js') }}"></script>
<script src="{{ asset('users/assets/plugin/select-flag/js/intlTelInput.min.js') }}"></script>
<script
    src="{{ asset('users/assets/js/signup-intl-phone.js') }}"
    data-iti-utils="{{ asset('users/assets/plugin/select-flag/js/utils.js') }}"
></script>
<script>
$(function () {
    function previewImage(input) {
        const file = input.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function () {
            $("#profile_pic_preview").attr("src", reader.result).removeClass("h-10 w-10 opacity-60").addClass("h-full w-full object-cover absolute inset-0");
            $("#profile_pic_string").val(reader.result.toString().replace(/^data:(.*,)?/, ""));
            $("#upload_profile").addClass("border-solid border-lime bg-white p-0");
        };
        reader.readAsDataURL(file);
    }

    $("#profile_pic").on("change", function () {
        previewImage(this);
        $(this).valid();
    });

    initSignupIntlPhone({
        inputSelector: "#phone_number",
        countrySelector: "#country",
        initialCountry: "ng",
    });

    $("#frm_signup").validate({
        ignore: [],
        normalizer: function (value) {
            return $.trim(value);
        },
        rules: {
            // SC-08: profile_pic intentionally omitted — optional
            first_name: { required: true, minlength: 1 },
            sur_name: { required: true, minlength: 1 },
            phone_number: { required: true, intlPhone: true },
            email: { required: true, email: true },
            password: { required: true, minlength: 7 },
            confirm_password: { required: true, equalTo: "#password" },
            street: { required: true },
            city_state: { required: true },
            country: { required: true },
            gdpr_consent: { required: true },
        },
        messages: {
            first_name: { required: "First name is required.", minlength: "Enter your first name." },
            sur_name: { required: "Surname is required.", minlength: "Enter your surname." },
            phone_number: {
                required: "Phone is required.",
                intlPhone: "Enter a valid phone number for the selected country.",
            },
            email: { required: "Email is required.", email: "Enter a valid email with a domain (e.g. name@example.com)." },
            password: { required: "Password is required.", minlength: "At least 7 characters." },
            confirm_password: { required: "Please confirm password.", equalTo: "Passwords do not match." },
            street: { required: "Street is required." },
            city_state: { required: "City/State is required." },
            country: { required: "Country is required." },
            gdpr_consent: { required: "Please accept the Terms, Privacy Policy, and GDPR information." },
        },
        errorPlacement: function (error, element) {
            const id = element.attr("name") === "profile_pic" ? "error_profile_pic" : (element.attr("name") === "gdpr_consent" ? "error_gdpr_consent" : "error_" + element.attr("name"));
            $("#" + id).html(error);
        }
    });

    $("#frm_signup").on("submit", function (e) {
        e.preventDefault();
        if (!$(this).valid()) return;

        $("#btn_signup_text").addClass("hidden");
        $("#btn_signup_loader").removeClass("hidden").addClass("inline-flex");
        $("#btn_signup").prop("disabled", true);

        const location = $("#street").val() + ", " + $("#city_state").val() + ", " + $("#country").val();
        const phoneE164 = getSignupIntlPhoneE164("#phone_number");

        const payload = {
                users_customers_type: "Individual",
                first_name: $("#first_name").val(),
                last_name: $("#sur_name").val(),
                phone: phoneE164,
                email: $("#email").val(),
                password: $("#password").val(),
                location: location,
                gdpr_consent: $("#gdpr_consent").is(":checked") ? "yes" : "",
            };
        const profilePic = $("#profile_pic_string").val();
        if (profilePic) {
            payload.profile_pic = profilePic;
        }

        $.ajax({
            url: "{{ rtrim(config('app.api_url'), '/') }}/signup",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(payload),
            success: function (response) {
                if (response.status === "error") {
                    toastr.error(response.message);
                    $("#btn_signup_text").removeClass("hidden");
                    $("#btn_signup_loader").addClass("hidden").removeClass("inline-flex");
                    $("#btn_signup").prop("disabled", false);
                    return;
                }
                window.location.href = "/users/verification_code/" + response.data.users_customers_id;
            },
            error: function (xhr) {
                var message = "Registration failed. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
                $("#btn_signup_text").removeClass("hidden");
                $("#btn_signup_loader").addClass("hidden").removeClass("inline-flex");
                $("#btn_signup").prop("disabled", false);
            }
        });
    });
});
</script>
@endpush
