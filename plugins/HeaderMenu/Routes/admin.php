<?php
/**
 * admin.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-04 16:17:53
 * @modified   2022-08-04 16:17:53
 */

use Illuminate\Support\Facades\Route;
use Plugin\HeaderMenu\Controllers\MenusController;

Route::get('/routes', [MenusController::class, 'getRoutes'])->name('routes');
