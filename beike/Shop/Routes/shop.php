<?php

use Beike\Shop\Http\Controllers\Account\AccountController;
use Beike\Shop\Http\Controllers\Account\LoginController;
use Beike\Shop\Http\Controllers\Account\LogoutController;
use Beike\Shop\Http\Controllers\Account\RegisterController;
use Beike\Shop\Http\Controllers\CartController;
use Beike\Shop\Http\Controllers\CategoryController;
use Beike\Shop\Http\Controllers\HomeController;
use Beike\Shop\Http\Controllers\PagesController;
use Beike\Shop\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('/')
    ->name('shop.')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home.index');

        Route::get('carts', [CartController::class, 'store'])->name('carts.store');

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

        Route::get('login', [LoginController::class, 'index'])->name('login.index');
        Route::post('login', [LoginController::class, 'store'])->name('login.store');
        Route::get('register', [RegisterController::class, 'index'])->name('register.index');
        Route::post('register', [RegisterController::class, 'store'])->name('register.store');
        Route::get('logout', [LogoutController::class, 'index'])->name('logout');

        Route::middleware('shop_auth:'.\Beike\Models\Customer::AUTH_GUARD)
            ->group(function () {
                Route::get('account', [AccountController::class, 'index'])->name('account.index');
            });


        Route::get('/{url_key}',[PagesController::class, 'show'])->name('pages.show');
    });
