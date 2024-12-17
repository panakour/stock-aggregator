<?php

namespace App\Fetchers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlphaVantageFetcher implements Fetcher
{
    private const RETRY_DELAY = 1000;

    public function fetchLatestPrice(string $symbol): ?float
    {
        $apiKey = config('services.alpha_vantage.key');

        try {
            $response = Http::timeout(5)
                ->retry(3, self::RETRY_DELAY)
                ->get('https://www.alphavantage.co/query', [
                    'function' => 'GLOBAL_QUOTE',
                    'symbol' => $symbol,
                    'apikey' => $apiKey,
                ]);

            if ($response->ok() && isset($response['Global Quote']['05. price'])) {
                return (float) $response['Global Quote']['05. price'];
            }

            Log::warning('Unexpected Alpha Vantage response for symbol: '.$symbol, [
                'body' => $response->body(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching Alpha Vantage data: '.$e->getMessage());
        }

        return null;
    }
}
