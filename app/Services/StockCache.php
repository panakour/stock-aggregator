<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class StockCache
{
    private const TTL = 60;

    public function remember(string $symbol, float $currentPrice, ?float $previousPrice, string $fetchedAt): void
    {
        Cache::put($this->key($symbol), [
            'current_price' => $currentPrice,
            'previous_price' => $previousPrice,
            'fetched_at' => $fetchedAt,
        ], self::TTL);
    }

    public function get(string $symbol): ?array
    {
        return Cache::get($this->key($symbol));
    }

    private function key(string $symbol): string
    {
        return "stock_price:{$symbol}";
    }
}
