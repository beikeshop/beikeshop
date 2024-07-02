<?php
use Beike\Models\Customer;
use Plugin\Upsell\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::middleware('checkout_auth:' . Customer::AUTH_GUARD)
->group(function () {
    Route::post('carts1', [CartController::class, 'store'])->name('carts1.store');
    // Route::put('carts/{cart}', [CartController::class, 'update'])->name('carts.update');
    // Route::post('carts/select', [CartController::class, 'select'])->name('carts.select');
    // Route::post('carts/unselect', [CartController::class, 'unselect'])->name('carts.unselect');
    // Route::delete('carts/{cart}', [CartController::class, 'destroy'])->name('carts.destroy');

    // Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    // Route::put('checkout', [CheckoutController::class, 'update'])->name('checkout.update');
    // Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    // Route::post('checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    // Route::get('orders/{number}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    // Route::post('orders/{number}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    // Route::post('orders/{number}/complete', [OrderController::class, 'complete'])->name('orders.complete');
});
