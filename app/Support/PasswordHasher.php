<?php

namespace App\Support;

use Illuminate\Support\Facades\Hash;

/**
 * Dual-verify password helper: bcrypt for new hashes, MD5 legacy with upgrade-on-login.
 */
class PasswordHasher
{
    public static function hash(string $plain): string
    {
        try {
            return Hash::make($plain);
        } catch (\Throwable $e) {
            return password_hash($plain, PASSWORD_BCRYPT);
        }
    }

    public static function check(string $plain, ?string $stored): bool
    {
        if ($stored === null || $stored === '') {
            return false;
        }

        if (self::isBcrypt($stored)) {
            try {
                return Hash::check($plain, $stored);
            } catch (\Throwable $e) {
                return password_verify($plain, $stored);
            }
        }

        // Legacy MD5
        return hash_equals(strtolower($stored), md5($plain));
    }

    public static function needsRehash(?string $stored): bool
    {
        if ($stored === null || $stored === '') {
            return true;
        }

        if (!self::isBcrypt($stored)) {
            return true;
        }

        try {
            return Hash::needsRehash($stored);
        } catch (\Throwable $e) {
            return password_needs_rehash($stored, PASSWORD_BCRYPT);
        }
    }

    public static function isBcrypt(string $stored): bool
    {
        return str_starts_with($stored, '$2y$')
            || str_starts_with($stored, '$2a$')
            || str_starts_with($stored, '$2b$');
    }
}
