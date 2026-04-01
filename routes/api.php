<?php

use App\Http\Controllers\Api\DropSafesController;
use App\Http\Controllers\Api\RegisterDropsController;
use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\CashOnHandsController;
use App\Http\Controllers\Api\TipsController;

use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'ok']);
});

Route::post('register-drops/add-time-out', [RegisterDropsController::class, 'addTimeOut']);
Route::post('register-drops/bulk-time-out-update', [RegisterDropsController::class, 'bulkTimeOutUpdate']);

Route::apiResource('register-drops', RegisterDropsController::class);
Route::apiResource('drop-safes', DropSafesController::class);

Route::apiResource('expenses', ExpensesController::class);

Route::get('tips/template', [TipsController::class, 'downloadTemplate']);
Route::apiResource('tips', TipsController::class);

Route::get('cashtrack/daily-summaries', [CashOnHandsController::class, 'index']);