<?php

namespace App\Support;

class CoverPricing
{
    /**
     * Product catalog price is the monthly base.
     * Annual / Yearly cover is billed as 12 × monthly.
     */
    public static function basePrice(object $product): ?float
    {
        foreach (['custom_price', 'price'] as $field) {
            if (isset($product->{$field}) && is_numeric($product->{$field})) {
                return (float) $product->{$field};
            }
        }

        return null;
    }

    public static function isAnnualCover(?string $coverDuration): bool
    {
        $value = strtolower(trim((string) $coverDuration));

        return $value !== ''
            && (
                str_contains($value, 'year')
                || str_contains($value, 'annual')
                || str_contains($value, '365')
            );
    }

    public static function multiplier(?string $coverDuration): int
    {
        return self::isAnnualCover($coverDuration) ? 12 : 1;
    }

    public static function billedPrice(object $product, ?string $coverDuration = null): ?float
    {
        $base = self::basePrice($product);
        if ($base === null) {
            return null;
        }

        return round($base * self::multiplier($coverDuration), 2);
    }

    public static function coverLabel(?string $coverDuration): string
    {
        return self::isAnnualCover($coverDuration) ? 'Annual' : 'Monthly';
    }
}
