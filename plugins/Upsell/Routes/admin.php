<?php
use Illuminate\Support\Facades\Route;

// 路由目录，插件自定义路由。admin.php 存放管理后台路由, shop.php 存放前台路由
Route::get("/plugins/upsell/welcome", function () {
    return view("Upsell::admin.welcome");
});

// Route::get("/plugins/upsell/edit", function () {
//     return view("Upsell::admin.edit");
// });