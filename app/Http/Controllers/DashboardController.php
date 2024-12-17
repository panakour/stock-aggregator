<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Database\Seeders\StockSeeder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stocks = Cache::rememberForever(StockSeeder::CACHE_KEY, function () {
            return Stock::select('symbol', 'name')
                ->orderBy('symbol')
                ->get();
        });

        return view('stock-items', compact('stocks'));
    }
}
