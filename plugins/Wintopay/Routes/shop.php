<?php
/**
 * wintopay.php
 *
 * @copyright  2024 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2024-05-13 18:33:13
 * @modified   2024-05-13 18:33:13
 */

use Beike\Shop\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use Plugin\Ecpay\Controllers\EcpayController;
use Plugin\Wintopay\Controllers\WintopayController;

Route::name('plugin.wintopay.')
    ->group(function () {
        Route::post('/wintopay/{id}', [WintopayController::class, 'pay'])->name('pay');
        Route::post('callback/wintopay', [WintopayController::class, 'notify'])->name('notify');
        Route::get('callback/wintopay', [WintopayController::class, 'notify'])->name('notify_get');
    });
