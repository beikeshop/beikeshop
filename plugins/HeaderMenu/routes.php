<?php
/**
 * route.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-21 09:35:05
 * @modified   2022-07-21 09:35:05
 */

use Illuminate\Support\Facades\Route;

Route::get('/latest_products', '\Plugin\HeaderMenu\Controllers\MenusController@latestProducts')->name('plugin.latest_products');

