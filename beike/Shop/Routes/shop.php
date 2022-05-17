<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/')
    ->name('shop.')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/', [Beike\Shop\Http\Controllers\HomeController::class, 'index'])->name('home.index');

        Route::get('carts', [Beike\Shop\Http\Controllers\CartController::class, 'store'])->name('carts.store');

        Route::get('categories/{category}', [Beike\Shop\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');

        Route::get('products/{product}', [Beike\Shop\Http\Controllers\ProductController::class, 'show'])->name('products.show');
    });
