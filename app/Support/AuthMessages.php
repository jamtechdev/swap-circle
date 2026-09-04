<?php

namespace App\Support;

/**
 * SC-03: Single source for portal auth copy (login, reset, shared errors).
 */
class AuthMessages
{
    public static function loginFailed(): string
    {
        return (string) __('auth.portal_login_failed');
    }

    public static function resetLinkSent(): string
    {
        return (string) __('auth.portal_reset_sent');
    }

    public static function emailRequired(): string
    {
        return (string) __('auth.portal_email_required');
    }

    public static function emailInvalid(): string
    {
        return (string) __('auth.portal_email_invalid');
    }

    public static function emailNotFound(): string
    {
        return (string) __('auth.portal_email_not_found');
    }

    public static function userNotFound(): string
    {
        return (string) __('auth.portal_user_not_found');
    }

    public static function verifyEmailRequired(): string
    {
        return (string) __('auth.portal_verify_required');
    }
}
