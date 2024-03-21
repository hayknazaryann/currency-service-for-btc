<?php

use App\Http\Controllers\CurrencyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.token')->prefix('v1')->group(function () {
    Route::get('rates', [CurrencyController::class, 'rates']);
    Route::post('convert', [CurrencyController::class, 'convert']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
