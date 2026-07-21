<?php

/*
 * @FilePath: admin.php
 *
 * @copyright     2024 beikeshop.com - All Rights Reserved.
 * @link: https://beikeshop.com
 * @Author: pu shuo <pushuo@guangda.work>
 * @Date: 2024-10-11 11:12:31
 * @LastEditTime: 2024-12-30 14:00:56
 */

/**
 * admin.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-04 16:17:53
 * @modified   2022-08-04 16:17:53
 */

use Illuminate\Support\Facades\Route;
use Plugin\BkAi\Controllers\AdminBkAi;
use Plugin\BkAi\Controllers\AdminMjAiImageController;

Route::post('/mj_ai_image/setting', [AdminMjAiImageController::class, 'saveSetting'])->name('plugin.mj_ai_image.setting');
Route::post('/mj_ai_image/generateImage', [AdminMjAiImageController::class, 'generateImage'])->name('plugin.mj_ai_image.generate_image');
Route::get('/mj_ai_image/checkImage', [AdminMjAiImageController::class, 'checkImage'])->name('plugin.mj_ai_image.check_image');
Route::post('/mj_ai_image/saveImage', [AdminMjAiImageController::class, 'saveImage'])->name('plugin.mj_ai_image.save_image');

Route::get('/bk_ai/index', [AdminBkAi::class, 'index'])->name('plugin.bk_ai.index');
Route::post('/bk_ai/generate', [AdminBkAi::class, 'generate'])->name('plugin.bk_ai.generate');

Route::get('/bk_ai/get_quota', [AdminBkAi::class, 'getQuota'])->name('plugin.bk_ai.get_quota');
