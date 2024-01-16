<?php

use Beike\Models\Customer;
use Beike\Shop\Http\Controllers\Account\AccountController;
use Beike\Shop\Http\Controllers\Account\AddressController;
use Beike\Shop\Http\Controllers\Account\EditController;
use Beike\Shop\Http\Controllers\Account\ForgottenController;
use Beike\Shop\Http\Controllers\Account\LoginController;
use Beike\Shop\Http\Controllers\Account\LogoutController;
use Beike\Shop\Http\Controllers\Account\OrderController;
use Beike\Shop\Http\Controllers\Account\PasswordController;
use Beike\Shop\Http\Controllers\Account\RegisterController;
use Beike\Shop\Http\Controllers\Account\RmaController;
use Beike\Shop\Http\Controllers\Account\WishlistController;
use Beike\Shop\Http\Controllers\BrandController;
use Beike\Shop\Http\Controllers\CartController;
use Beike\Shop\Http\Controllers\CategoryController;
use Beike\Shop\Http\Controllers\CheckoutController;
use Beike\Shop\Http\Controllers\CurrencyController;
use Beike\Shop\Http\Controllers\FileController;
use Beike\Shop\Http\Controllers\HomeController;
use Beike\Shop\Http\Controllers\LanguageController;
use Beike\Shop\Http\Controllers\PageCategoryController;
use Beike\Shop\Http\Controllers\PageController;
use Beike\Shop\Http\Controllers\PluginController;
use Beike\Shop\Http\Controllers\ProductController;
use Beike\Shop\Http\Controllers\ZoneController;
use Illuminate\Support\Facades\Route;

Route::prefix('/')
    ->name('shop.')
    ->middleware(['shop'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home.index');

        Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
        Route::get('brands/autocomplete', [BrandController::class, 'autocomplete'])->name('brands.autocomplete');
        Route::get('brands/{id}', [BrandController::class, 'show'])->name('brands.show');

        Route::get('carts/mini', [CartController::class, 'miniCart'])->name('carts.mini');

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

        Route::get('countries/{id}/zones', [ZoneController::class, 'index'])->name('countries.zones.index');

        Route::get('currency/{currency}', [CurrencyController::class, 'index'])->name('currency.switch');

        Route::post('files', [FileController::class, 'store'])->name('file.store');
        Route::get('forgotten', [ForgottenController::class, 'index'])->name('forgotten.index');
        Route::post('forgotten/send_code', [ForgottenController::class, 'sendVerifyCode'])->name('forgotten.send_code');
        Route::post('forgotten/password', [ForgottenController::class, 'changePassword'])->name('forgotten.password');

        Route::get('lang/{lang}', [LanguageController::class, 'index'])->name('lang.switch');

        Route::get('login', [LoginController::class, 'index'])->name('login.index');
        Route::post('login', [LoginController::class, 'store'])->name('login.store');
        Route::get('logout', [LogoutController::class, 'index'])->name('logout');

        Route::get('orders/{number}', [\Beike\Shop\Http\Controllers\OrderController::class, 'show'])->name('orders.show');

        Route::get('page_categories', [PageCategoryController::class, 'home'])->name('page_categories.home');
        Route::get('page_categories/{page_category}', [PageCategoryController::class, 'show'])->name('page_categories.show');
        Route::get('pages/{page}', [PageController::class, 'show'])->name('pages.show');

        Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

        Route::get('register', [RegisterController::class, 'index'])->name('register.index');
        Route::post('register', [RegisterController::class, 'store'])->name('register.store');

        Route::get('plugin/{code}/{path}', [PluginController::class, 'asset'])->where('path', '(.*)')->name('plugin.asset');

        Route::middleware('checkout_auth:' . Customer::AUTH_GUARD)
            ->group(function () {
                Route::get('carts', [CartController::class, 'index'])->name('carts.index');
                Route::post('carts', [CartController::class, 'store'])->name('carts.store');
                Route::put('carts/{cart}', [CartController::class, 'update'])->name('carts.update');
                Route::post('carts/select', [CartController::class, 'select'])->name('carts.select');
                Route::post('carts/unselect', [CartController::class, 'unselect'])->name('carts.unselect');
                Route::delete('carts/{cart}', [CartController::class, 'destroy'])->name('carts.destroy');

                Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
                Route::put('checkout', [CheckoutController::class, 'update'])->name('checkout.update');
                Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
                Route::post('checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
                Route::get('orders/{number}/pay', [OrderController::class, 'pay'])->name('orders.pay');
                Route::post('orders/{number}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
                Route::post('orders/{number}/complete', [OrderController::class, 'complete'])->name('orders.complete');
            });

        Route::prefix('account')
            ->name('account.')
            ->middleware('shop_auth:' . Customer::AUTH_GUARD)
            ->group(function () {
                Route::get('/', [AccountController::class, 'index'])->name('index');
                Route::resource('addresses', AddressController::class);
                Route::get('edit', [EditController::class, 'index'])->name('edit.index');

                Route::get('password', [PasswordController::class, 'index'])->name('password.index');
                Route::post('password', [PasswordController::class, 'update'])->name('password.update');

                Route::get('rmas', [RmaController::class, 'index'])->name('rma.index');
                Route::get('rmas/{id}', [RmaController::class, 'show'])->name('rma.show');
                Route::get('rmas/create/{order_product_id}', [RmaController::class, 'create'])->name('rma.create');
                Route::post('rmas/store', [RmaController::class, 'store'])->name('rma.store');

                Route::put('edit', [EditController::class, 'update'])->name('edit.update');
                Route::get('orders', [OrderController::class, 'index'])->name('order.index');
                Route::get('orders/{number}', [OrderController::class, 'show'])->name('order.show');
                Route::get('update_password', [AccountController::class, 'updatePassword'])->name('update_password');
                Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
                Route::post('wishlist', [WishlistController::class, 'add'])->name('wishlist.add');
                Route::delete('wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
            });
    });
