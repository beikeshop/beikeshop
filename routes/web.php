<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    echo phpinfo();
})->name('test');
