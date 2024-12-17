<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Repositories\StockRepository;
use Database\Seeders\StockSeeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class StockController extends Controller
{
    public function __construct(
        private readonly StockRepository $stocks
    ) {}

    public function index(): JsonResponse
    {
        $stocks = Cache::rememberForever(StockSeeder::CACHE_KEY, function () {
            return Stock::select('symbol', 'name')
                ->orderBy('symbol')
                ->get();
        });

        return response()->json($stocks);
    }

    public function show(string $symbol): JsonResponse
    {
        $data = $this->stocks->getLatestPrices($symbol);

        if (isset($data['message'])) {
            return response()->json($data, 404);
        }

        return response()->json($data);
    }
}
