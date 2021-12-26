<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home.index');

        Route::resource('products', \App\Http\Controllers\Admin\ProductsController::class);
    });
