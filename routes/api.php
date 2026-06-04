<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugin\Paypal\Controllers\PaypalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::match(['get', 'post'], '/paypal/nvp/return', [PaypalController::class, 'nvpReturn'])->name('paypal.nvp.return');
Route::match(['get', 'post'], '/paypal/nvp/cancel', [PaypalController::class, 'nvpCancel'])->name('paypal.nvp.cancel');
Route::post('/paypal/nvp/notify', [PaypalController::class, 'nvpNotify'])->name('paypal.nvp.notify');
