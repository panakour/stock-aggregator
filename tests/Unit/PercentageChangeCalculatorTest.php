<?php

namespace Tests\Unit;

use App\Services\PercentageChangeCalculator;
use PHPUnit\Framework\TestCase;

class PercentageChangeCalculatorTest extends TestCase
{
    public function test_returns_zero_when_previous_price_is_null(): void
    {
        $result = PercentageChangeCalculator::calculate(100.0, null);

        $this->assertEquals(0.0, $result);
    }

    public function test_calculates_positive_percentage_change(): void
    {
        $result = PercentageChangeCalculator::calculate(110.0, 100.0);

        $this->assertEquals(10.0, $result);
    }

    public function test_calculates_negative_percentage_change(): void
    {
        $result = PercentageChangeCalculator::calculate(90.0, 100.0);

        $this->assertEquals(-10.0, $result);
    }

    public function test_calculates_percentage_change_with_decimal_prices(): void
    {
        $result = PercentageChangeCalculator::calculate(150.75, 140.50);

        $this->assertEquals(7.295373665480427, $result);
    }

    public function test_calculates_percentage_change_with_same_prices(): void
    {
        $result = PercentageChangeCalculator::calculate(100.0, 100.0);

        $this->assertEquals(0.0, $result);
    }
}
