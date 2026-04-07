<?php

use App\Http\Controllers\Api\AdminUsersController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlazeAccountingSummariesController;
use App\Http\Controllers\Api\CashlessAtmEntriesController;
use App\Http\Controllers\Api\CashlessAtmReconcilesController;
use App\Http\Controllers\Api\CashlessAtmReconciliationController;
use App\Http\Controllers\Api\CashOnHandsController;
use App\Http\Controllers\Api\DropSafesController;
use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\RegisterDropsController;
use App\Http\Controllers\Api\StoresController;
use App\Http\Controllers\Api\TipsController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'ok']);
});

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/users', [AdminUsersController::class, 'index']);
        Route::post('/users', [AdminUsersController::class, 'store']);
        Route::patch('/users/{user}/role', [AdminUsersController::class, 'updateRole']);
    });

    // Cash track & resources: admin, manager, and user roles. Admins also pass via EnsureUserHasRole (full access).
    Route::middleware(['role:admin,manager,user'])->group(function () {
        Route::post('register-drops/add-time-out', [RegisterDropsController::class, 'addTimeOut']);
        Route::post('register-drops/bulk-time-out-update', [RegisterDropsController::class, 'bulkTimeOutUpdate']);

        Route::apiResource('register-drops', RegisterDropsController::class);
        Route::apiResource('drop-safes', DropSafesController::class);
        Route::apiResource('cashless-atm-entries', CashlessAtmEntriesController::class);
        Route::apiResource('cashless-atm-reconciles', CashlessAtmReconcilesController::class);
        Route::get('cashless-atm-reconciliation', [CashlessAtmReconciliationController::class, 'index']);

        Route::get('blaze-accounting-summaries', [BlazeAccountingSummariesController::class, 'index']);

        Route::apiResource('expenses', ExpensesController::class);

        Route::apiResource('stores', StoresController::class);

        Route::get('tips/template', [TipsController::class, 'downloadTemplate']);
        Route::apiResource('tips', TipsController::class);

        Route::get('cashtrack/daily-summaries', [CashOnHandsController::class, 'index']);
    });
});
