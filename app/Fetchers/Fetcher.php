<?php

namespace App\Fetchers;

interface Fetcher
{
    public function fetchLatestPrice(string $symbol): ?float;
}
