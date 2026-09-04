<?php

namespace App\Support;

/**
 * SC-04: Stricter email checks — require a domain with a real TLD (reject user@domain, a@b, localhost).
 */
class EmailAddress
{
    public static function normalize(?string $email): string
    {
        return strtolower(trim((string) $email));
    }

    public static function isValid(?string $email): bool
    {
        $email = self::normalize($email);
        if ($email === '') {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $at = strrpos($email, '@');
        if ($at === false) {
            return false;
        }

        $domain = substr($email, $at + 1);
        if ($domain === false || $domain === '') {
            return false;
        }

        $domain = strtolower($domain);

        if ($domain === 'localhost' || str_ends_with($domain, '.localhost')) {
            return false;
        }

        // Must contain a dot and a TLD of at least 2 chars (blocks user@domain / a@b)
        if (!preg_match('/^(?:[a-z0-9](?:[a-z0-9\-]*[a-z0-9])?\.)+[a-z]{2,}$/i', $domain)) {
            return false;
        }

        return true;
    }
}
