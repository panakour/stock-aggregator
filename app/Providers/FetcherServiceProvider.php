<?php

namespace App\Providers;

use App\Console\Commands\FetchFakePrices;
use App\Fetchers\AlphaVantageFetcher;
use App\Fetchers\FakeFetcher;
use App\Fetchers\Fetcher;
use Illuminate\Support\ServiceProvider;

class FetcherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(Fetcher::class, AlphaVantageFetcher::class);

        // Contextual binding for the fake prices command
        $this->app->when(FetchFakePrices::class)
            ->needs(Fetcher::class)
            ->give(FakeFetcher::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
