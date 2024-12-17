<?php

namespace App\Jobs;

use App\Fetchers\Fetcher;
use App\Models\Stock;
use App\Models\StockPrice;
use App\Services\StockCache;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FetchStockPrice implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Fetcher $fetcher,
        private readonly StockCache $cache)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $stocks = Stock::select('id', 'symbol')->get();

        foreach ($stocks as $stock) {
            $fetchedAt = Carbon::now();
            $newPrice = $this->fetcher->fetchLatestPrice($stock->symbol);

            if ($newPrice === null) {
                Log::warning('Failed to fetch stock price', [
                    'symbol' => $stock->symbol,
                    'timestamp' => $fetchedAt->toDateTimeString(),
                ]);

                continue;
            }

            $previousPrice = StockPrice::where('stock_id', $stock->id)
                ->orderBy('fetched_at', 'desc')
                ->value('price');

            StockPrice::create([
                'stock_id' => $stock->id,
                'price' => $newPrice,
                'fetched_at' => $fetchedAt,
            ]);

            $this->cache->remember(
                $stock->symbol,
                $newPrice,
                $previousPrice,
                $fetchedAt->toDateTimeString()
            );
        }
    }
}
