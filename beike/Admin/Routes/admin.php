<?php

use Beike\Admin\Http\Controllers;
use Beike\Admin\Http\Controllers\ForgottenController;
use Illuminate\Support\Facades\Route;

$adminName = admin_name();
Route::prefix($adminName)
    ->middleware(['web'])
    ->name("{$adminName}.")
    ->group(function () {
        Route::get('login', [Controllers\LoginController::class, 'show'])->name('login.show');
        Route::post('login', [Controllers\LoginController::class, 'store'])->name('login.store');

        Route::get('forgotten', [ForgottenController::class, 'index'])->name('forgotten.index');
        Route::post('forgotten/send_code', [ForgottenController::class, 'sendVerifyCode'])->name('forgotten.send_code');
        Route::post('forgotten/password', [ForgottenController::class, 'changePassword'])->name('forgotten.password');

        Route::middleware('admin_auth:' . \Beike\Models\AdminUser::AUTH_GUARD)
            ->group(function () {
                Route::get('/', [Controllers\HomeController::class, 'index'])->name('home.index');

                Route::Resource('categories', Controllers\CategoryController::class);
                Route::get('design/builder', [Controllers\DesignController::class, 'index'])->name('design.index');
                Route::put('design/builder', [Controllers\DesignController::class, 'update'])->name('design.update');
                Route::post('design/builder/preview', [Controllers\DesignController::class, 'preview'])->name('design.module.preview');

                Route::Resource('files', Controllers\FileController::class);

                Route::get('file_manager', [Controllers\FileManagerController::class, 'index'])->name('file_manager.index');
                Route::post('file_manager/directory', [Controllers\FileManagerController::class, 'createDirectory'])->name('file_manager.create_directory');
                Route::post('file_manager/upload', [Controllers\FileManagerController::class, 'uploadFiles'])->name('file_manager.upload');
                Route::post('file_manager/rename', [Controllers\FileManagerController::class, 'rename'])->name('file_manager.rename');
                Route::delete('file_manager/delete_files', [Controllers\FileManagerController::class, 'destroyFiles'])->name('file_manager.delete_files');

                Route::Resource('customers', Controllers\CustomerController::class);
                Route::resource('customers.addresses', Controllers\AddressController::class);
                Route::resource('countries.zones', Controllers\ZoneController::class);
                Route::Resource('customer_groups', Controllers\CustomerGroupController::class);

                Route::put('products/restore', [Controllers\ProductController::class, 'restore']);
                Route::get('categories/{id}/name', [Controllers\CategoryController::class, 'name'])->name('categories.name');
                Route::get('products/{id}/name', [Controllers\ProductController::class, 'name'])->name('products.name');
                Route::get('products/names', [Controllers\ProductController::class, 'getNames'])->name('products.names');
                Route::resource('products', Controllers\ProductController::class);
                Route::resource('currencies', Controllers\CurrencyController::class);

                Route::get('orders', [Controllers\OrderController::class, 'index'])->name('orders.index');
                Route::get('orders/{order}', [Controllers\OrderController::class, 'show'])->name('orders.show');

                Route::get('settings', [Controllers\SettingController::class, 'index'])->name('settings.index');

                Route::get('plugins', [Controllers\PluginController::class, 'index'])->name('plugins.index');
                Route::get('plugins/{code}/edit', [Controllers\PluginController::class, 'edit'])->name('plugins.edit');
                Route::put('plugins/{code}', [Controllers\PluginController::class, 'update'])->name('plugins.update');
                Route::put('plugins/{code}/status', [Controllers\PluginController::class, 'updateStatus'])->name('plugins.update_status');
                Route::post('plugins/{code}/install', [Controllers\PluginController::class, 'install'])->name('plugins.install');
                Route::post('plugins/{code}/uninstall', [Controllers\PluginController::class, 'uninstall'])->name('plugins.uninstall');

                Route::get('logout', [Controllers\LogoutController::class, 'index'])->name('logout.index');

            });
    });
