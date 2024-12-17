<?php

namespace Tests\Feature;

use App\Models\Stock;
use App\Repositories\StockRepository;
use Database\Seeders\StockSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(StockSeeder::class);

        $mockRepository = Mockery::mock(StockRepository::class);
        $mockRepository->shouldReceive('getLatestPrices')
            ->andReturn([
                'symbol' => 'AAPL',
                'current_price' => 150.00,
                'previous_price' => 145.00,
                'percentage_change' => 3.45,
            ]);

        $this->app->instance(StockRepository::class, $mockRepository);

        $this->seed(StockSeeder::class);
    }

    public function test_dashboard_page_loads_successfully(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('stock-items');
        $response->assertViewHas('stocks');
    }

    public function test_dashboard_contains_all_stock_components(): void
    {
        $response = $this->get('/dashboard');

        $stocks = Stock::all();

        foreach ($stocks as $stock) {
            $response->assertSee($stock->symbol);
            $response->assertSee($stock->name);
        }
    }

    public function test_dashboard_uses_cached_stocks(): void
    {
        $this->get('/dashboard');
        Stock::query()->delete();
        $response = $this->get('/dashboard');
        $response->assertSee('AAPL');
    }

}
