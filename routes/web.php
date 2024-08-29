<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use Illuminate\Support\Facades\Route;

$baseMiddlewares = [
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
];

Route::get('/', fn() => redirect('/dashboard'));

Route::middleware($baseMiddlewares)->group(function () {
    Route::get('/dashboard', OrderController::class)->name('dashboard');
    Route::resource('orders', OrderController::class);
    Route::resource('orders-detail', OrderDetailController::class);
    Route::get('orders/{order}/show', [OrderController::class, 'showById']);
});
