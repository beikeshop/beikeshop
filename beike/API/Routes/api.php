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

use Beike\API\Controllers\Admin as AdminControllers;
use Beike\API\Controllers as ShopControllers;
use Illuminate\Support\Facades\Route;

// Shop API controllers
Route::prefix('api')
    ->middleware(['api'])
    ->name('api.')
    ->group(function () {
        Route::post('login', [ShopControllers\AuthController::class, 'login']);

        Route::post('logout', [ShopControllers\AuthController::class, 'logout']);
        Route::post('refresh', [ShopControllers\AuthController::class, 'refresh']);
        Route::get('me', [ShopControllers\AuthController::class, 'me']);

        Route::get('home', [ShopControllers\HomeController::class, 'index']);
        Route::get('products', [ShopControllers\ProductController::class, 'index']);
        Route::get('products/{product}', [ShopControllers\ProductController::class, 'show']);

        Route::get('carts', [ShopControllers\CartController::class, 'index']);
        Route::post('carts', [ShopControllers\CartController::class, 'store']);
        Route::put('carts/{cart}', [ShopControllers\CartController::class, 'update']);
        Route::delete('carts/{cart}', [ShopControllers\CartController::class, 'destroy']);

        Route::get('checkout', [ShopControllers\CheckoutController::class, 'index']);
        Route::put('checkout', [ShopControllers\CheckoutController::class, 'update']);
        Route::post('checkout/confirm', [ShopControllers\CheckoutController::class, 'confirm']);

        Route::get('countries', [ShopControllers\CountryController::class, 'index']);
        Route::get('countries/{country}/zones', [ShopControllers\CountryController::class, 'zones']);

        Route::get('addresses', [ShopControllers\AddressController::class, 'index']);
        Route::get('addresses/{address}', [ShopControllers\AddressController::class, 'show']);
        Route::post('addresses', [ShopControllers\AddressController::class, 'store']);
        Route::put('addresses/{address}', [ShopControllers\AddressController::class, 'update']);
        Route::delete('addresses/{address}', [ShopControllers\AddressController::class, 'destroy']);

        Route::get('orders', [ShopControllers\OrderController::class, 'index']);
        Route::get('orders/{order}', [ShopControllers\OrderController::class, 'show']);

        Route::get('wishlists', [ShopControllers\WishlistController::class, 'index']);
        Route::post('wishlists', [ShopControllers\WishlistController::class, 'store']);
        Route::delete('wishlists/{wishlist}', [ShopControllers\WishlistController::class, 'destroy']);
    });

// Admin API controllers
Route::prefix('admin_api')
    ->middleware(['admin_api'])
    ->name('admin_api.')
    ->group(function () {
        Route::get('brands', [AdminControllers\BrandController::class, 'index'])->name('brands.index');
        Route::get('brands/{brand}', [AdminControllers\BrandController::class, 'show'])->name('brands.show');
        Route::post('brands', [AdminControllers\BrandController::class, 'store'])->name('brands.create');
        Route::put('brands/{brand}', [AdminControllers\BrandController::class, 'update'])->name('brands.update');
        Route::delete('brands/{brand}', [AdminControllers\BrandController::class, 'destroy'])->name('brands.delete');

        Route::get('categories', [AdminControllers\CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [AdminControllers\CategoryController::class, 'show'])->name('categories.show');
        Route::post('categories', [AdminControllers\CategoryController::class, 'store'])->name('categories.create');
        Route::put('categories/{category}', [AdminControllers\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [AdminControllers\CategoryController::class, 'destroy'])->name('categories.delete');

        Route::get('me', [AdminControllers\UserController::class, 'me'])->name('me');

        Route::get('orders', [AdminControllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminControllers\OrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}/status', [AdminControllers\OrderController::class, 'updateStatus'])->name('orders.update_status');
        Route::put('orders/{order}/shipments/{shipment}', [AdminControllers\OrderController::class, 'updateShipment'])->name('orders.update_shipment');

        Route::get('products', [AdminControllers\ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [AdminControllers\ProductController::class, 'show'])->name('products.show');
        Route::post('products', [AdminControllers\ProductController::class, 'store'])->name('products.create');
        Route::put('products/{product}', [AdminControllers\ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [AdminControllers\ProductController::class, 'destroy'])->name('products.delete');
    });
