<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ProductionController;
use App\Http\Controllers\Web\RawMaterialController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('products', ProductController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('raw-materials', RawMaterialController::class)->parameters(['raw-materials' => 'rawMaterial'])->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('clients', ClientController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('productions', ProductionController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::resource('orders', OrderController::class)->only(['index', 'store', 'show', 'destroy']);
});
