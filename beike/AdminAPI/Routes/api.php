<?php
/**
 * api.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-04-11 17:36:05
 * @modified   2023-04-11 17:36:05
 */

use Beike\AdminAPI\Controllers as AdminController;
use Illuminate\Support\Facades\Route;

// Admin API controllers
Route::prefix('admin_api')
    ->middleware(['admin_api'])
    ->name('admin_api.')
    ->group(function () {
        Route::get('brands', [AdminController\BrandController::class, 'index'])->name('brands.index');
        Route::get('brands/{brand}', [AdminController\BrandController::class, 'show'])->name('brands.show');
        Route::post('brands', [AdminController\BrandController::class, 'store'])->name('brands.create');
        Route::put('brands/{brand}', [AdminController\BrandController::class, 'update'])->name('brands.update');
        Route::delete('brands/{brand}', [AdminController\BrandController::class, 'destroy'])->name('brands.delete');

        Route::get('categories', [AdminController\CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [AdminController\CategoryController::class, 'show'])->name('categories.show');
        Route::post('categories', [AdminController\CategoryController::class, 'store'])->name('categories.create');
        Route::put('categories/{category}', [AdminController\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [AdminController\CategoryController::class, 'destroy'])->name('categories.delete');

        Route::get('me', [AdminController\UserController::class, 'me'])->name('me');

        Route::get('orders', [AdminController\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminController\OrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}/status', [AdminController\OrderController::class, 'updateStatus'])->name('orders.update_status');
        Route::put('orders/{order}/shipments/{shipment}', [AdminController\OrderController::class, 'updateShipment'])->name('orders.update_shipment');

        Route::get('products', [AdminController\ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [AdminController\ProductController::class, 'show'])->name('products.show');
        Route::post('products', [AdminController\ProductController::class, 'store'])->name('products.create');
        Route::put('products/{product}', [AdminController\ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [AdminController\ProductController::class, 'destroy'])->name('products.delete');
    });
