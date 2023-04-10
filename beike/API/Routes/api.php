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

use Illuminate\Support\Facades\Route;

Route::prefix('api')
    ->middleware(['api'])
    ->name('api.')
    ->group(function () {
        Route::post('login', [\Beike\API\Controllers\AuthController::class, 'login']);
        Route::post('logout', [\Beike\API\Controllers\AuthController::class, 'logout']);
        Route::post('refresh', [\Beike\API\Controllers\AuthController::class, 'refresh']);
        Route::get('me', [\Beike\API\Controllers\AuthController::class, 'me']);
    });

Route::prefix('admin_api')
    ->middleware(['admin_api'])
    ->name('admin_api.')
    ->group(function () {
        Route::get('brands', [\Beike\API\Controllers\Admin\BrandController::class, 'index'])->name('brands.index');
        Route::get('brands/{brand}', [\Beike\API\Controllers\Admin\BrandController::class, 'show'])->name('brands.show');
        Route::post('brands', [\Beike\API\Controllers\Admin\BrandController::class, 'store'])->name('brands.create');
        Route::put('brands/{brand}', [\Beike\API\Controllers\Admin\BrandController::class, 'update'])->name('brands.update');
        Route::delete('brands/{brand}', [\Beike\API\Controllers\Admin\BrandController::class, 'destroy'])->name('brands.delete');

        Route::get('categories', [\Beike\API\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [\Beike\API\Controllers\Admin\CategoryController::class, 'show'])->name('categories.show');
        Route::post('categories', [\Beike\API\Controllers\Admin\CategoryController::class, 'store'])->name('categories.create');
        Route::put('categories/{category}', [\Beike\API\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [\Beike\API\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.delete');

        Route::get('me', [\Beike\API\Controllers\Admin\UserController::class, 'me'])->name('me');

        Route::get('orders', [\Beike\API\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [\Beike\API\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}/status', [\Beike\API\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update_status');
        Route::put('orders/{order}/shipments/{shipment}', [\Beike\API\Controllers\Admin\OrderController::class, 'updateShipment'])->name('orders.update_shipment');

        Route::get('products', [\Beike\API\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [\Beike\API\Controllers\Admin\ProductController::class, 'show'])->name('products.show');
        Route::post('products', [\Beike\API\Controllers\Admin\ProductController::class, 'store'])->name('products.create');
        Route::put('products/{product}', [\Beike\API\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [\Beike\API\Controllers\Admin\ProductController::class, 'destroy'])->name('products.delete');
    });
