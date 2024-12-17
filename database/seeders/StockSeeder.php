<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class StockSeeder extends Seeder
{
    public const CACHE_KEY = 'stocks.list';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $symbols = [
            ['symbol' => 'AAPL', 'name' => 'Apple Inc'],
            ['symbol' => 'GOOGL', 'name' => 'Alphabet Inc'],
            ['symbol' => 'MSFT', 'name' => 'Microsoft Corp'],
            ['symbol' => 'AMZN', 'name' => 'Amazon.com Inc'],
            ['symbol' => 'TSLA', 'name' => 'Tesla Inc'],
            ['symbol' => 'META', 'name' => 'Meta Platforms Inc'],
            ['symbol' => 'NFLX', 'name' => 'Netflix Inc'],
            ['symbol' => 'NVDA', 'name' => 'NVIDIA Corp'],
            ['symbol' => 'BAC', 'name' => 'Bank of America Corp'],
            ['symbol' => 'WMT', 'name' => 'Walmart Inc'],
        ];

        foreach ($symbols as $s) {
            Stock::updateOrCreate(
                ['symbol' => $s['symbol']],
                ['name' => $s['name']]
            );
        }

        Cache::forever(self::CACHE_KEY, collect($symbols));

    }
}
