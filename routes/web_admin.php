<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('login', [\App\Http\Controllers\Admin\LoginController::class, 'show'])->name('login.show');
        Route::post('login', [\App\Http\Controllers\Admin\LoginController::class, 'store'])->name('login.store');

        Route::middleware('auth:'.\App\Models\AdminUser::AUTH_GUARD)
            ->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home.index');

                Route::resource('products', \App\Http\Controllers\Admin\ProductsController::class);
            });
    });
