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
use Plugin\Openai\Controllers\OpenaiController;

Route::middleware('can:openai_index')->get('/openai', [OpenaiController::class, 'index'])->name('openai.index');

Route::middleware('can:openai_index')->get('/openai/histories', [OpenaiController::class, 'histories'])->name('openai.histories');
Route::middleware('can:openai_index')->post('/openai/completions', [OpenaiController::class, 'completions'])->name('openai.completions');
