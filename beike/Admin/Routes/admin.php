<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['web'])
    ->name('admin.')
    ->group(function () {
        Route::get('login', [\Beike\Admin\Http\Controllers\LoginController::class, 'show'])->name('login.show');
        Route::post('login', [\Beike\Admin\Http\Controllers\LoginController::class, 'store'])->name('login.store');

        Route::middleware('admin_auth:' . \Beike\Models\AdminUser::AUTH_GUARD)
            ->group(function () {
                Route::get('/', [\Beike\Admin\Http\Controllers\HomeController::class, 'index'])->name('home.index');

                Route::Resource('categories', \Beike\Admin\Http\Controllers\CategoryController::class);

                Route::Resource('files', \Beike\Admin\Http\Controllers\FileController::class);

                Route::Resource('customers', \Beike\Admin\Http\Controllers\CustomerController::class);
                Route::resource('customers.addresses', \Beike\Admin\Http\Controllers\AddressController::class);

                Route::put('products/restore', [\Beike\Admin\Http\Controllers\ProductController::class, 'restore']);
                Route::resource('products', \Beike\Admin\Http\Controllers\ProductController::class);

                Route::get('settings', [\Beike\Admin\Http\Controllers\SettingController::class, 'index'])->name('settings.index');

                Route::get('plugins', [\Beike\Admin\Http\Controllers\PluginController::class, 'index'])->name('plugins.index');
                Route::get('plugins/{code}/edit', [\Beike\Admin\Http\Controllers\PluginController::class, 'edit'])->name('plugins.edit');

                Route::get('logout', [\Beike\Admin\Http\Controllers\LogoutController::class, 'index'])->name('logout.index');


            });
    });
