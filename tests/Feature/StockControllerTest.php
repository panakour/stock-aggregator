<?php

namespace Tests\Feature;

use App\Models\Stock;
use App\Models\StockPrice;
use App\Services\StockCache;
use Database\Seeders\StockSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(StockSeeder::class);
    }

    public function test_index_returns_cached_stocks_list(): void
    {
        $response = $this->getJson('/api/stocks');

        $response->assertStatus(200)
            ->assertJsonCount(10)
            ->assertJsonStructure([
                '*' => ['symbol', 'name'],
            ]);

        $response->assertJsonFragment([
            'symbol' => 'AAPL',
            'name' => 'Apple Inc',
        ]);
    }

    public function test_show_returns_stock_price_data(): void
    {
        $stock = Stock::where('symbol', 'AAPL')->first();

        StockPrice::create([
            'stock_id' => $stock->id,
            'price' => 150.00,
            'fetched_at' => now()->subMinutes(2),
        ]);

        StockPrice::create([
            'stock_id' => $stock->id,
            'price' => 155.00,
            'fetched_at' => now(),
        ]);

        $response = $this->getJson('/api/stocks/AAPL');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'symbol',
                'current_price',
                'previous_price',
                'percentage_change',
            ]);

        $data = $response->json();
        $this->assertEquals('AAPL', $data['symbol']);
        $this->assertEquals(155.00, $data['current_price']);
        $this->assertEquals(150.00, $data['previous_price']);
        $this->assertEqualsWithDelta(3.33, $data['percentage_change'], 0.01);
    }

    public function test_show_returns_404_for_invalid_symbol(): void
    {
        $this->getJson('/api/stocks/INVALID')
            ->assertStatus(404)
            ->assertExactJson(['message' => 'No data']);
    }

    public function test_show_uses_cached_data_when_available(): void
    {
        app(StockCache::class)->remember('AAPL', 200.00, 190.00, now()->toDateTimeString());

        $response = $this->getJson('/api/stocks/AAPL');

        $response->assertStatus(200);

        $data = $response->json();
        $this->assertEquals('AAPL', $data['symbol']);
        $this->assertEquals(200.00, $data['current_price']);
        $this->assertEquals(190.00, $data['previous_price']);
        $this->assertEqualsWithDelta(5.26, $data['percentage_change'], 0.01);
    }
}
