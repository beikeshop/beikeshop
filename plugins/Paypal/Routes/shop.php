<?php
/**
 * shop.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
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
