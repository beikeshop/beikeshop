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
                Route::get('design/builder', [\Beike\Admin\Http\Controllers\DesignController::class, 'index'])->name('design.index');

                Route::Resource('files', \Beike\Admin\Http\Controllers\FileController::class);

                Route::Resource('filemanager', \Beike\Admin\Http\Controllers\FilemanagerController::class);

                Route::Resource('customers', \Beike\Admin\Http\Controllers\CustomerController::class);
                Route::resource('customers.addresses', \Beike\Admin\Http\Controllers\AddressController::class);
                Route::resource('countries.zones', \Beike\Admin\Http\Controllers\ZoneController::class);
                Route::Resource('customer_groups', \Beike\Admin\Http\Controllers\CustomerGroupController::class);

                Route::put('products/restore', [\Beike\Admin\Http\Controllers\ProductController::class, 'restore']);
                Route::resource('products', \Beike\Admin\Http\Controllers\ProductController::class);
                Route::resource('currencies', \Beike\Admin\Http\Controllers\CurrencyController::class);

                Route::get('orders', [\Beike\Admin\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
                Route::get('orders/{order}', [\Beike\Admin\Http\Controllers\OrderController::class, 'show'])->name('orders.show');

                Route::get('settings', [\Beike\Admin\Http\Controllers\SettingController::class, 'index'])->name('settings.index');

                Route::get('plugins', [\Beike\Admin\Http\Controllers\PluginController::class, 'index'])->name('plugins.index');
                Route::get('plugins/{code}/edit', [\Beike\Admin\Http\Controllers\PluginController::class, 'edit'])->name('plugins.edit');
                Route::put('plugins/{code}', [\Beike\Admin\Http\Controllers\PluginController::class, 'update'])->name('plugins.update');
                Route::put('plugins/{code}/status', [\Beike\Admin\Http\Controllers\PluginController::class, 'updateStatus'])->name('plugins.update_status');
                Route::post('plugins/{code}/install', [\Beike\Admin\Http\Controllers\PluginController::class, 'install'])->name('plugins.install');
                Route::post('plugins/{code}/uninstall', [\Beike\Admin\Http\Controllers\PluginController::class, 'uninstall'])->name('plugins.uninstall');

                Route::get('logout', [\Beike\Admin\Http\Controllers\LogoutController::class, 'index'])->name('logout.index');


            });
    });
