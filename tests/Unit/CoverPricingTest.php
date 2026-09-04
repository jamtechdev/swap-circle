<?php

namespace Tests\Unit;

use App\Support\CoverPricing;
use PHPUnit\Framework\TestCase;

class CoverPricingTest extends TestCase
{
    public function test_monthly_uses_base_price(): void
    {
        $product = (object) ['price' => 100, 'custom_price' => 100];

        $this->assertSame(100.0, CoverPricing::billedPrice($product, 'Monthly'));
        $this->assertSame(1, CoverPricing::multiplier('Monthly'));
    }

    public function test_annual_multiplies_by_twelve(): void
    {
        $product = (object) ['price' => 100, 'custom_price' => 100];

        $this->assertSame(1200.0, CoverPricing::billedPrice($product, 'Yearly'));
        $this->assertSame(1200.0, CoverPricing::billedPrice($product, 'Annual'));
        $this->assertSame(12, CoverPricing::multiplier('Yearly'));
    }

    public function test_custom_price_preferred_over_price(): void
    {
        $product = (object) ['price' => 50, 'custom_price' => 100];

        $this->assertSame(1200.0, CoverPricing::billedPrice($product, 'Yearly'));
    }
}
