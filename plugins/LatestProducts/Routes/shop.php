<?php
/**
 * shop.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-04 16:17:44
 * @modified   2022-08-04 16:17:44
 */

use Illuminate\Support\Facades\Route;
use Plugin\LatestProducts\Controllers\MenusController;

Route::get('/latest_products', [MenusController::class, 'latestProducts'])->name('plugin.latest_products');
