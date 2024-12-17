<?php

namespace App\Console\Commands;

use App\Fetchers\Fetcher;
use App\Jobs\FetchStockPrice;
use App\Services\StockCache;
use Illuminate\Console\Command;

class FetchFakePrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-fake-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will fetch prices from the faker fetcher';

    /**
     * Execute the console command.
     */
    public function handle(Fetcher $fetcher, StockCache $cache): void
    {
        FetchStockPrice::dispatch($fetcher, $cache);
        $this->info('Fake price fetch job dispatched');
    }
}
