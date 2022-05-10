<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/')
    ->name('shop.')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/', [Beike\Http\Controllers\Shop\HomeController::class, 'index'])->name('home.index');

        Route::get('carts', [Beike\Http\Controllers\Shop\CartController::class, 'store'])->name('carts.store');

        Route::get('categories/{category}', [Beike\Http\Controllers\Shop\CategoryController::class, 'show'])->name('categories.show');

        Route::get('products/{product}', [Beike\Http\Controllers\Shop\ProductController::class, 'show'])->name('products.show');
    });
