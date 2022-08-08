<?php
/**
 * shop.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-04 16:17:44
 * @modified   2022-08-04 16:17:44
 */

use Illuminate\Support\Facades\Route;
use Plugin\Stripe\Controllers\StripeController;

Route::post('/stripe/capture', [StripeController::class, 'capture'])->name('plugin.stripe_capture');
