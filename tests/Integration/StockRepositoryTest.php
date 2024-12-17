<?php

namespace Tests\Integration;

use App\Models\Stock;
use App\Models\StockPrice;
use App\Repositories\StockRepository;
use App\Services\StockCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private StockRepository $repository;

    private StockCache $cache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cache = $this->mock(StockCache::class);
        $this->repository = new StockRepository($this->cache);
    }

    public function test_returns_cached_data_when_available(): void
    {
        $this->cache->expects('get')
            ->with('AAPL')
            ->andReturn([
                'current_price' => '150.00',
                'previous_price' => '140.00',
                'fetched_at' => now()->toDateTimeString(),
            ]);

        $result = $this->repository->getLatestPrices('AAPL');

        $this->assertEquals([
            'symbol' => 'AAPL',
            'current_price' => '150.00',
            'previous_price' => '140.00',
            'percentage_change' => 7.142857142857142,
        ], $result);
    }

    public function test_falls_back_to_database_when_cache_misses(): void
    {
        $this->cache->expects('get')->with('AAPL')->andReturnNull();

        $stock = Stock::create(['symbol' => 'AAPL', 'name' => 'Apple Inc']);

        StockPrice::create([
            'stock_id' => $stock->id,
            'price' => '150.00',
            'fetched_at' => now(),
        ]);

        StockPrice::create([
            'stock_id' => $stock->id,
            'price' => '140.00',
            'fetched_at' => now()->subMinute(),
        ]);

        $result = $this->repository->getLatestPrices('AAPL');

        $this->assertEquals([
            'symbol' => 'AAPL',
            'current_price' => '150.00',
            'previous_price' => '140.00',
            'percentage_change' => 7.142857142857142,
        ], $result);
    }

    public function test_when_stock_has_no_prices(): void
    {
        $this->cache->expects('get')->with('AAPL')->andReturnNull();
        Stock::create(['symbol' => 'AAPL', 'name' => 'Apple Inc']);

        $result = $this->repository->getLatestPrices('AAPL');

        $this->assertEquals(['message' => 'No data'], $result);
    }
}
