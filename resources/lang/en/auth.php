<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    // Portal customer auth (SC-02 / SC-03) — keep login + reset copy in one place
    'portal_login_failed' => 'The email or password is incorrect.',
    'portal_reset_sent' => 'If an account exists for this email, a reset link has been sent.',
    'portal_email_required' => 'Please enter email address.',
    'portal_email_invalid' => 'Enter a valid email with a domain and extension (e.g. name@example.com).',
    'portal_email_not_found' => 'Email does not exist.',
    'portal_user_not_found' => 'User does not exist.',
    'portal_verify_required' => 'Please verify your email before signing in. Check your inbox for the verification code or request a new one.',

];
