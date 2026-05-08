<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ExternalCatalogController;
use App\Http\Controllers\Api\ExternalQuoteController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\InternalDashboardController;
use App\Http\Controllers\Api\InternalStockAlertController;
use App\Http\Controllers\Api\ProductionController;
use App\Http\Controllers\Api\RawMaterialController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::prefix('external')->as('api.external.')->group(function () {
    Route::get('/catalog', ExternalCatalogController::class)->name('catalog');
    Route::post('/quotes', ExternalQuoteController::class)->name('quotes');
});

Route::middleware('api.token')->as('api.')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('me');

    Route::prefix('internal')->as('internal.')->group(function () {
        Route::get('/dashboard-summary', InternalDashboardController::class)->name('dashboard-summary');
        Route::get('/stock-alerts', InternalStockAlertController::class)->name('stock-alerts');
    });

    Route::apiResource('users', UserController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('raw-materials', RawMaterialController::class)->parameters(['raw-materials' => 'rawMaterial']);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('productions', ProductionController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'show', 'destroy']);
});
