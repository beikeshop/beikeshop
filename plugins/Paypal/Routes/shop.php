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
    Route::get('/test', [PaypalController::class, 'test']);
    Route::post('/create', [PaypalController::class, 'create']);
    Route::post('/capture', [PaypalController::class, 'capture']);
});
