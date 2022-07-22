<?php

use Beike\Models\Customer;
use Illuminate\Support\Facades\Route;
use Beike\Shop\Http\Controllers\ZoneController;
use Beike\Shop\Http\Controllers\CartController;
use Beike\Shop\Http\Controllers\HomeController;
use Beike\Shop\Http\Controllers\PagesController;
use Beike\Shop\Http\Controllers\ProductController;
use Beike\Shop\Http\Controllers\CategoryController;
use Beike\Shop\Http\Controllers\CheckoutController;
use Beike\Shop\Http\Controllers\Account\LoginController;
use Beike\Shop\Http\Controllers\Account\OrderController;
use Beike\Shop\Http\Controllers\Account\LogoutController;
use Beike\Shop\Http\Controllers\Account\AddressController;
use Beike\Shop\Http\Controllers\Account\AccountController;
use Beike\Shop\Http\Controllers\Account\RegisterController;
use Beike\Shop\Http\Controllers\Account\ForgottenController;


Route::prefix('/')
    ->name('shop.')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home.index');

        Route::get('carts', [CartController::class, 'index'])->name('carts.index');
        Route::post('carts', [CartController::class, 'store'])->name('carts.store');
        Route::get('carts/mini', [CartController::class, 'miniCart'])->name('carts.mini');
        Route::put('carts/{cart}', [CartController::class, 'update'])->name('carts.update');
        Route::post('carts/select', [CartController::class, 'select'])->name('carts.select');
        Route::delete('carts/{cart}', [CartController::class, 'destroy'])->name('carts.destroy');

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

        Route::get('countries.zones', [ZoneController::class, 'index'])->name('countries.zones.index');

        Route::get('forgotten', [ForgottenController::class, 'index'])->name('forgotten.index');
        Route::post('forgotten/send_code', [ForgottenController::class, 'sendVerifyCode'])->name('forgotten.send_code');
        Route::post('forgotten/password', [ForgottenController::class, 'changePassword'])->name('forgotten.password');

        Route::get('login', [LoginController::class, 'index'])->name('login.index');
        Route::post('login', [LoginController::class, 'store'])->name('login.store');
        Route::get('logout', [LogoutController::class, 'index'])->name('logout');

        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

        Route::get('register', [RegisterController::class, 'index'])->name('register.index');
        Route::post('register', [RegisterController::class, 'store'])->name('register.store');

        Route::middleware('shop_auth:' . Customer::AUTH_GUARD)
            ->group(function () {
                Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
                Route::put('checkout', [CheckoutController::class, 'update'])->name('checkout.update');
                Route::post('checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
                Route::get('orders/{number}/success', [OrderController::class, 'success'])->name('orders.success');
                Route::get('orders/{number}/pay', [OrderController::class, 'pay'])->name('orders.pay');
                Route::post('orders/{number}/pay', [OrderController::class, 'capture'])->name('orders.capture');
            });

        Route::prefix('account')
            ->middleware('shop_auth:' . Customer::AUTH_GUARD)
            ->group(function () {
                Route::get('/', [AccountController::class, 'index'])->name('account.index');
                Route::resource('addresses', AddressController::class);
                Route::get('orders', [OrderController::class, 'index'])->name('account.order.index');
                Route::get('orders/{number}', [OrderController::class, 'show'])->name('account.order.show');
                Route::post('update', [AccountController::class, 'update'])->name('account.update');
                Route::get('update_password', [AccountController::class, 'updatePassword'])->name('account.update_password');
            });
    });

Route::get('/{url_key}', [PagesController::class, 'show'])->name('pages.show');
