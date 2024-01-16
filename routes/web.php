<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    echo __FILE__;
})->name('test');
