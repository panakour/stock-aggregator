<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::prefix('api')->group(function () {
    Route::get('/stocks', [StockController::class, 'index']);
    Route::get('/stocks/{symbol}', [StockController::class, 'show']);
});
