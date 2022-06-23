<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/')
    ->name('shop.')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/', [Beike\Shop\Http\Controllers\HomeController::class, 'index'])->name('home.index');

        Route::get('carts', [Beike\Shop\Http\Controllers\CartController::class, 'store'])->name('carts.store');

        Route::get('categories', [Beike\Shop\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [Beike\Shop\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');

        Route::get('products/{product}', [Beike\Shop\Http\Controllers\ProductController::class, 'show'])->name('products.show');

        Route::get('login', [Beike\Shop\Http\Controllers\LoginController::class, 'index'])->name('login.index');
        Route::post('login', [Beike\Shop\Http\Controllers\LoginController::class, 'store'])->name('login.store');

        Route::middleware('auth:'.\Beike\Models\Customer::AUTH_GUARD)
            ->group(function () {

            });
    });
