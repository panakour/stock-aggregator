<?php

namespace App\Services;

class PercentageChangeCalculator
{
    public static function calculate(float $currentPrice, ?float $previousPrice): float
    {
        if ($previousPrice === null) {
            return 0.0;
        }

        return (($currentPrice - $previousPrice) / $previousPrice) * 100.0;
    }
}
