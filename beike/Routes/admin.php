<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['web'])
    ->name('admin.')
    ->group(function () {
        Route::get('login', [\Beike\Http\Controllers\Admin\LoginController::class, 'show'])->name('login.show');
        Route::post('login', [\Beike\Http\Controllers\Admin\LoginController::class, 'store'])->name('login.store');

        Route::middleware('auth:'.\Beike\Models\AdminUser::AUTH_GUARD)
            ->group(function () {
                Route::get('/', [\Beike\Http\Controllers\Admin\HomeController::class, 'index'])->name('home.index');

                Route::Resource('categories', \Beike\Http\Controllers\Admin\CategoriesController::class);

                Route::put('products/restore', [\Beike\Http\Controllers\Admin\ProductsController::class, 'restore']);
                Route::resource('products', \Beike\Http\Controllers\Admin\ProductsController::class);

                Route::get('logout', [\Beike\Http\Controllers\Admin\LogoutController::class, 'index'])->name('logout.index');


            });
    });
