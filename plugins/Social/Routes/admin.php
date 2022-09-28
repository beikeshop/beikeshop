<?php
/**
 * admin.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-04 16:17:53
 * @modified   2022-08-04 16:17:53
 */

use Illuminate\Support\Facades\Route;
use Plugin\Social\Controllers\AdminSocialController;

Route::post('/social/setting', [AdminSocialController::class, 'saveSetting'])->name('plugin.social.setting');
