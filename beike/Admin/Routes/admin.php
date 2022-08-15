<?php

use Beike\Admin\Http\Controllers;
use Beike\Admin\Http\Controllers\ForgottenController;
use Illuminate\Support\Facades\Route;

$adminName = admin_name();
Route::prefix($adminName)
    ->middleware(['admin'])
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

                Route::get('brands/names', [Controllers\BrandController::class, 'getNames'])->name('brands.names');
                Route::get('brands/autocomplete', [Controllers\BrandController::class, 'autocomplete'])->name('brands.autocomplete');
                Route::resource('brands', Controllers\BrandController::class);
                Route::get('brands/{id}/name', [Controllers\BrandController::class, 'name'])->name('brands.name');

                Route::get('categories/autocomplete', [Controllers\CategoryController::class, 'autocomplete'])->name('categories.autocomplete');
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

                Route::get('design_footer/builder', [Controllers\DesignFooterController::class, 'index'])->name('design_footer.index');
                Route::put('design_footer/builder', [Controllers\DesignFooterController::class, 'update'])->name('design_footer.update');
                Route::post('design_footer/builder/preview', [Controllers\DesignFooterController::class, 'preview'])->name('design_footer.module.preview');

                Route::get('design_menu/builder', [Controllers\DesignMenuController::class, 'index'])->name('design_menu.index');
                Route::put('design_menu/builder', [Controllers\DesignMenuController::class, 'update'])->name('design_menu.update');

                Route::put('edit', [Controllers\EditController::class, 'update'])->name('edit');
                Route::get('edit/locale', [Controllers\EditController::class, 'locale'])->name('edit.locale');

                Route::get('file_manager', [Controllers\FileManagerController::class, 'index'])->name('file_manager.index');
                Route::get('file_manager/files', [Controllers\FileManagerController::class, 'getFiles'])->name('file_manager.get_files');
                Route::get('file_manager/directories', [Controllers\FileManagerController::class, 'getDirectories'])->name('file_manager.get_directories');
                Route::post('file_manager/directories', [Controllers\FileManagerController::class, 'createDirectory'])->name('file_manager.create_directory');
                Route::post('file_manager/upload', [Controllers\FileManagerController::class, 'uploadFiles'])->name('file_manager.upload');
                Route::post('file_manager/rename', [Controllers\FileManagerController::class, 'rename'])->name('file_manager.rename');
                Route::delete('file_manager/files', [Controllers\FileManagerController::class, 'destroyFiles'])->name('file_manager.delete_files');
                Route::delete('file_manager/directories', [Controllers\FileManagerController::class, 'destroyDirectories'])->name('file_manager.delete_directories');

                Route::get('logout', [Controllers\LogoutController::class, 'index'])->name('logout.index');
                Route::resource('languages', Controllers\LanguageController::class);


                // 订单
                Route::middleware('can:orders_index')->get('orders', [Controllers\OrderController::class, 'index'])->name('orders.index');
                Route::middleware('can:orders_show')->get('orders/{order}', [Controllers\OrderController::class, 'show'])->name('orders.show');
                Route::middleware('can:orders_update_status')->put('orders/{order}/status', [Controllers\OrderController::class, 'updateStatus'])->name('orders.update_status');


                // 插件
                Route::middleware('can:plugins_index')->get('plugins', [Controllers\PluginController::class, 'index'])->name('plugins.index');
                Route::middleware('can:plugins_import')->post('plugins/import', [Controllers\PluginController::class, 'import'])->name('plugins.import');
                Route::middleware('can:plugins_show')->get('plugins/{code}/edit', [Controllers\PluginController::class, 'edit'])->name('plugins.edit');
                Route::middleware('can:plugins_update')->put('plugins/{code}', [Controllers\PluginController::class, 'update'])->name('plugins.update');
                Route::middleware('can:plugins_update_status')->put('plugins/{code}/status', [Controllers\PluginController::class, 'updateStatus'])->name('plugins.update_status');
                Route::middleware('can:plugins_install')->post('plugins/{code}/install', [Controllers\PluginController::class, 'install'])->name('plugins.install');
                Route::middleware('can:plugins_uninstall')->post('plugins/{code}/uninstall', [Controllers\PluginController::class, 'uninstall'])->name('plugins.uninstall');


                // 单页
                Route::middleware('can:pages_index')->get('pages', [Controllers\PagesController::class, 'index'])->name('pages.index');
                Route::middleware('can:pages_index')->get('pages/autocomplete', [Controllers\PagesController::class, 'autocomplete'])->name('pages.autocomplete');
                Route::middleware('can:pages_create')->get('pages/create', [Controllers\PagesController::class, 'create'])->name('pages.create');
                Route::middleware('can:pages_show')->get('pages/{code}/edit', [Controllers\PagesController::class, 'edit'])->name('pages.edit');
                Route::middleware('can:pages_show')->get('pages/{page}/name', [Controllers\PagesController::class, 'name'])->name('pages.name');
                Route::middleware('can:pages_create')->post('pages', [Controllers\PagesController::class, 'store'])->name('pages.store');
                Route::middleware('can:pages_update')->put('pages/{page}', [Controllers\PagesController::class, 'update'])->name('pages.update');
                Route::middleware('can:pages_delete')->delete('pages/{page}', [Controllers\PagesController::class, 'destroy'])->name('pages.destroy');


                // 产品
                Route::put('products/restore', [Controllers\ProductController::class, 'restore']);
                Route::get('products/trashed', [Controllers\ProductController::class, 'trashed'])->name('products.trashed');
                Route::get('products/{id}/name', [Controllers\ProductController::class, 'name'])->name('products.name');
                Route::get('products/names', [Controllers\ProductController::class, 'getNames'])->name('products.names');
                Route::get('products/autocomplete', [Controllers\ProductController::class, 'autocomplete'])->name('products.autocomplete');
                Route::resource('products', Controllers\ProductController::class);

                Route::resource('regions', Controllers\RegionController::class);
                Route::post('rmas/history/{id}', [Controllers\RmaController::class, 'addHistory'])->name('rmas.add_history');
                Route::resource('rmas', Controllers\RmaController::class);
                Route::resource('rma_reasons', Controllers\RmaReasonController::class);

                Route::get('settings', [Controllers\SettingController::class, 'index'])->name('settings.index');
                Route::post('settings', [Controllers\SettingController::class, 'store'])->name('settings.store');


                // 税类
                Route::middleware('can:tax_classes_index')->get('tax_classes', [Controllers\TaxClassController::class, 'index'])->name('tax_classes.index');
                Route::middleware('can:tax_classes_create')->post('tax_classes', [Controllers\TaxClassController::class, 'store'])->name('tax_classes.store');
                Route::middleware('can:tax_classes_update')->put('tax_classes/{tax_class}', [Controllers\TaxClassController::class, 'update'])->name('tax_classes.update');
                Route::middleware('can:tax_classes_delete')->delete('tax_classes/{tax_class}', [Controllers\TaxClassController::class, 'destroy'])->name('tax_classes.destroy');


                // 税费
                Route::middleware('can:tax_rates_index')->get('tax_rates', [Controllers\TaxRateController::class, 'index'])->name('tax_rates.index');
                Route::middleware('can:tax_rates_create')->post('tax_rates', [Controllers\TaxRateController::class, 'store'])->name('tax_rates.store');
                Route::middleware('can:tax_rates_update')->put('tax_rates/{tax_rate}', [Controllers\TaxRateController::class, 'update'])->name('tax_rates.update');
                Route::middleware('can:tax_rates_delete')->delete('tax_rates/{tax_rate}', [Controllers\TaxRateController::class, 'destroy'])->name('tax_rates.destroy');

                Route::resource('admin_users', Controllers\AdminUserController::class);
                Route::resource('admin_roles', Controllers\AdminRoleController::class);
            });
    });
