<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/')
    ->name('shop.')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\Shop\HomeController::class, 'index'])->name('home.index');

        Route::get('carts', [App\Http\Controllers\Shop\CartsController::class, 'store'])->name('carts.store');

        Route::get('categories/{category}', [App\Http\Controllers\Shop\CategoriesController::class, 'show'])->name('categories.show');

        Route::get('products/{product}', [App\Http\Controllers\Shop\ProductsController::class, 'show'])->name('products.show');
    });
