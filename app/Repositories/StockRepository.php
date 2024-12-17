<?php

namespace App\Repositories;

use App\Models\Stock;
use App\Models\StockPrice;
use App\Services\PercentageChangeCalculator;
use App\Services\StockCache;

class StockRepository
{
    public function __construct(
        private readonly StockCache $cache
    ) {}

    public function getLatestPrices(string $symbol): array
    {
        if ($cached = $this->cache->get($symbol)) {
            return [
                'symbol' => $symbol,
                'current_price' => $cached['current_price'],
                'previous_price' => $cached['previous_price'],
                'percentage_change' => PercentageChangeCalculator::calculate(
                    $cached['current_price'],
                    $cached['previous_price']
                ),
            ];
        }

        // Fallback to database if not cached
        $stock = Stock::where('symbol', $symbol)->first();

        if (! $stock) {
            return ['message' => 'No data'];
        }

        $latest = StockPrice::where('stock_id', $stock->id)
            ->orderBy('fetched_at', 'desc')
            ->take(2)
            ->get();

        if ($latest->isEmpty()) {
            return ['message' => 'No data'];
        }

        return [
            'symbol' => $symbol,
            'current_price' => $latest->first()->price,
            'previous_price' => $latest->skip(1)->first()?->price,
            'percentage_change' => PercentageChangeCalculator::calculate(
                $latest->first()->price,
                $latest->skip(1)->first()?->price
            ),
        ];
    }
}
