<?php

namespace App\Fetchers;

class FakeFetcher implements Fetcher
{
    public function fetchLatestPrice(string $symbol): ?float
    {
        $basePrice = 200.0;

        // Generate a random price between -10% and +10%
        $fluctuation = $basePrice * (mt_rand(-1000, 1000) / 10000);

        return round($basePrice + $fluctuation, 2);
    }
}
