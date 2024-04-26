<?php
use Illuminate\Support\Facades\Route;
use Plugin\Clarity\Controllers\AdminClarityController;

Route::post('/clarity/setting', [AdminClarityController::class, 'saveSetting'])->name('plugin.clarity.setting');
