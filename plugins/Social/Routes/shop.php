<?php
/**
 * shop.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-09-30 18:52:54
 * @modified   2022-09-30 18:52:54
 */

use Illuminate\Support\Facades\Route;
use Plugin\Social\Controllers\ShopSocialController;

Route::get('/social/redirect/{provider}', [ShopSocialController::class, 'redirect'])->name('plugin.social.redirect');
Route::get('/social/callbacks/{provider}', [ShopSocialController::class, 'callback'])->name('plugin.social.callback');
