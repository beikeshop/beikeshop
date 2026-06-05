<?php
/**
 * shop.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-12 10:33:13
 * @modified   2022-08-12 10:33:13
 */

use Illuminate\Support\Facades\Route;
use Plugin\Paypal\Controllers\PaypalController;

Route::group(['prefix' => 'paypal'], function () {
    Route::post('/create', [PaypalController::class, 'create']);
    Route::post('/capture', [PaypalController::class, 'capture']);
    Route::match(['get', 'post'], '/nvp/return', [PaypalController::class, 'nvpReturn'])->name('paypal.nvp.return');
    Route::match(['get', 'post'], '/nvp/cancel', [PaypalController::class, 'nvpCancel'])->name('paypal.nvp.cancel');
    Route::post('/nvp/notify', [PaypalController::class, 'nvpNotify'])->name('paypal.nvp.notify');
});
