<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/')
    ->name('shop.')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\Shop\HomeController::class, 'index'])->name('home.index');

        Route::get('products/{product}', [App\Http\Controllers\Shop\ProductsController::class, 'show'])->name('products.show');
    });
