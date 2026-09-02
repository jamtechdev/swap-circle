<?php

namespace App\Support;

class BrandMark
{
    public const ICON = 'favico.png';

    public static function iconUrl(): string
    {
        return asset('uploads/system_image/' . self::ICON);
    }

    public static function logoUrl(?string $filename = null): string
    {
        return asset('uploads/system_image/' . ($filename ?: 'logo.png'));
    }
}
