<?php

use App\Http\Controllers\Api\DropSafesController;
use App\Http\Controllers\Api\RegisterDropsController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'ok']);
});

Route::post('register-drops/add-time-out', [RegisterDropsController::class, 'addTimeOut']);
Route::post('register-drops/bulk-time-out-update', [RegisterDropsController::class, 'bulkTimeOutUpdate']);

Route::apiResource('register-drops', RegisterDropsController::class);
Route::apiResource('drop-safes', DropSafesController::class);
