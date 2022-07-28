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

        Route::get('categories/autocomplete', [Controllers\CategoryController::class, 'autocomplete'])->name('categories.autocomplete');
        Route::get('products/autocomplete', [Controllers\ProductController::class, 'autocomplete'])->name('products.autocomplete');

        Route::middleware('admin_auth:' . \Beike\Models\AdminUser::AUTH_GUARD)
            ->group(function () {
                Route::get('/', [Controllers\HomeController::class, 'index'])->name('home.index');

                Route::resource('categories', Controllers\CategoryController::class);
                Route::get('categories/{id}/name', [Controllers\CategoryController::class, 'name'])->name('categories.name');

                Route::resource('customers', Controllers\CustomerController::class);
                Route::resource('customers.addresses', Controllers\AddressController::class);
                Route::resource('countries.zones', Controllers\ZoneController::class);
                Route::resource('customer_groups', Controllers\CustomerGroupController::class);

                Route::resource('currencies', Controllers\CurrencyController::class);

                Route::get('design/builder', [Controllers\DesignController::class, 'index'])->name('design.index');
                Route::put('design/builder', [Controllers\DesignController::class, 'update'])->name('design.update');
                Route::post('design/builder/preview', [Controllers\DesignController::class, 'preview'])->name('design.module.preview');

                Route::resource('files', Controllers\FileController::class);
                Route::resource('manufacturers', Controllers\ManufacturerController::class);

                Route::get('file_manager', [Controllers\FileManagerController::class, 'index'])->name('file_manager.index');
                Route::get('file_manager/files', [Controllers\FileManagerController::class, 'getFiles'])->name('file_manager.get_files');
                Route::get('file_manager/directories', [Controllers\FileManagerController::class, 'getDirectories'])->name('file_manager.get_directories');
                Route::post('file_manager/directories', [Controllers\FileManagerController::class, 'createDirectory'])->name('file_manager.create_directory');
                Route::post('file_manager/upload', [Controllers\FileManagerController::class, 'uploadFiles'])->name('file_manager.upload');
                Route::post('file_manager/rename', [Controllers\FileManagerController::class, 'rename'])->name('file_manager.rename');
                Route::delete('file_manager/files', [Controllers\FileManagerController::class, 'destroyFiles'])->name('file_manager.delete_files');
                Route::delete('file_manager/directories', [Controllers\FileManagerController::class, 'destroyDirectories'])->name('file_manager.delete_directories');

                Route::get('logout', [Controllers\LogoutController::class, 'index'])->name('logout.index');

                Route::get('orders', [Controllers\OrderController::class, 'index'])->name('orders.index');
                Route::get('orders/{order}', [Controllers\OrderController::class, 'show'])->name('orders.show');

                Route::get('plugins', [Controllers\PluginController::class, 'index'])->name('plugins.index');
                Route::post('plugins/import', [Controllers\PluginController::class, 'import'])->name('plugins.import');
                Route::get('plugins/{code}/edit', [Controllers\PluginController::class, 'edit'])->name('plugins.edit');
                Route::put('plugins/{code}', [Controllers\PluginController::class, 'update'])->name('plugins.update');
                Route::put('plugins/{code}/status', [Controllers\PluginController::class, 'updateStatus'])->name('plugins.update_status');
                Route::post('plugins/{code}/install', [Controllers\PluginController::class, 'install'])->name('plugins.install');
                Route::post('plugins/{code}/uninstall', [Controllers\PluginController::class, 'uninstall'])->name('plugins.uninstall');

                Route::put('products/restore', [Controllers\ProductController::class, 'restore']);
                Route::get('products/{id}/name', [Controllers\ProductController::class, 'name'])->name('products.name');
                Route::get('products/names', [Controllers\ProductController::class, 'getNames'])->name('products.names');
                Route::resource('products', Controllers\ProductController::class);

                Route::resource('regions', Controllers\RegionController::class);

                Route::get('settings', [Controllers\SettingController::class, 'index'])->name('settings.index');
                Route::post('settings', [Controllers\SettingController::class, 'store'])->name('settings.store');

                Route::resource('tax_classes', Controllers\TaxClassController::class);
                Route::resource('tax_rates', Controllers\TaxRateController::class);
            });
    });
