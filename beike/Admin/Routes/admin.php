<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['web'])
    ->name('admin.')
    ->group(function () {
        Route::get('login', [\Beike\Admin\Http\Controllers\LoginController::class, 'show'])->name('login.show');
        Route::post('login', [\Beike\Admin\Http\Controllers\LoginController::class, 'store'])->name('login.store');

        Route::middleware('auth:'.\Beike\Models\AdminUser::AUTH_GUARD)
            ->group(function () {
                Route::get('/', [\Beike\Admin\Http\Controllers\HomeController::class, 'index'])->name('home.index');

                Route::Resource('categories', \Beike\Admin\Http\Controllers\CategoryController::class);

                Route::put('products/restore', [\Beike\Admin\Http\Controllers\ProductController::class, 'restore']);
                Route::resource('products', \Beike\Admin\Http\Controllers\ProductController::class);

                Route::get('logout', [\Beike\Admin\Http\Controllers\LogoutController::class, 'index'])->name('logout.index');


            });
    });
