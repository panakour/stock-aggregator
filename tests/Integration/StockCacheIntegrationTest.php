<?php

namespace Tests\Integration;

use App\Services\StockCache;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class StockCacheIntegrationTest extends TestCase
{
    private StockCache $stockCache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stockCache = new StockCache;
        Cache::flush();
    }

    public function test_stores_and_retrieves_data_from_real_cache(): void
    {
        $symbol = 'AAPL';
        $currentPrice = 150.50;
        $previousPrice = 145.75;
        $fetchedAt = '2024-03-17 10:00:00';

        $this->stockCache->remember($symbol, $currentPrice, $previousPrice, $fetchedAt);
        $result = $this->stockCache->get($symbol);

        $this->assertEquals([
            'current_price' => $currentPrice,
            'previous_price' => $previousPrice,
            'fetched_at' => $fetchedAt,
        ], $result);
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }
}
