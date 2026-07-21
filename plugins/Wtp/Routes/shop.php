<?php

/**
 * wtp.php
 *
 * @copyright  2024 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2024-05-13 18:33:13
 * @modified   2024-05-13 18:33:13
 */

use Illuminate\Support\Facades\Route;
use Plugin\Wtp\Controllers\WtpController;

Route::name('plugin.wtp.')
    ->group(function () {
        Route::post('/wtp/{id}', [WtpController::class, 'pay'])->name('pay');
        Route::post('callback/wtp', [WtpController::class, 'notify'])->name('notify');
        Route::get('callback/wtp', [WtpController::class, 'notify'])->name('notify_get');
    });
