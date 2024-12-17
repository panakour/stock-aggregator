<?php

namespace Tests\Unit;

use App\Services\StockCache;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\TestCase;

class StockCacheTest extends TestCase
{
    private StockCache $stockCache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stockCache = new StockCache;
    }

    public function test_stores_stock_price_data_in_cache(): void
    {
        $symbol = 'AAPL';
        $currentPrice = 150.50;
        $previousPrice = 145.75;
        $fetchedAt = '2024-03-17 10:00:00';

        Cache::shouldReceive('put')
            ->once()
            ->with(
                'stock_price:AAPL',
                [
                    'current_price' => $currentPrice,
                    'previous_price' => $previousPrice,
                    'fetched_at' => $fetchedAt,
                ],
                60
            );

        $this->stockCache->remember($symbol, $currentPrice, $previousPrice, $fetchedAt);
    }

    public function test_retrieves_stock_price_data_from_cache(): void
    {
        $symbol = 'AAPL';
        $expectedData = [
            'current_price' => 150.50,
            'previous_price' => 145.75,
            'fetched_at' => '2024-03-17 10:00:00',
        ];

        Cache::shouldReceive('get')
            ->once()
            ->with('stock_price:AAPL')
            ->andReturn($expectedData);

        $result = $this->stockCache->get($symbol);

        $this->assertEquals($expectedData, $result);
    }

    public function test_returns_null_when_cache_is_empty(): void
    {
        $symbol = 'AAPL';
        Cache::shouldReceive('get')
            ->once()
            ->with('stock_price:AAPL')
            ->andReturnNull();

        $result = $this->stockCache->get($symbol);

        $this->assertNull($result);
    }

    public function test_stores_data_with_null_previous_price(): void
    {
        $symbol = 'AAPL';
        $currentPrice = 150.50;
        $fetchedAt = '2024-03-17 10:00:00';

        Cache::shouldReceive('put')
            ->once()
            ->with(
                'stock_price:AAPL',
                [
                    'current_price' => $currentPrice,
                    'previous_price' => null,
                    'fetched_at' => $fetchedAt,
                ],
                60
            );

        $this->stockCache->remember($symbol, $currentPrice, null, $fetchedAt);
    }

    public function test_uses_consistent_cache_key_format(): void
    {
        $symbol = 'AAPL';
        $expectedKey = 'stock_price:AAPL';

        Cache::shouldReceive('get')
            ->once()
            ->with($expectedKey);

        $this->stockCache->get($symbol);
    }
}
