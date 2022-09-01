const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/build/js')
//     .postCss('resources/css/app.css', 'public/build/css', [
//         //
//     ]);

// mix.sass('resources/css/app.scss', 'public/build/css');
// 后台

mix.sass('resources/beike/admin/css/bootstrap/bootstrap.scss', 'public/build/beike/admin/css/bootstrap.css');
mix.sass('resources/beike/admin/css/app.scss', 'public/build/beike/admin/css/app.css');
mix.js('resources/beike/admin/js/app.js', 'public/build/beike/admin/js/app.js');

// filemanager
mix.sass('resources/beike/admin/css/filemanager/app.scss', 'public/build/beike/admin/css/filemanager.css');

// 前端 default 模版
mix.sass('resources/beike/shop/default/css/bootstrap/bootstrap.scss', 'public/build/beike/shop/default/css/bootstrap.css');
mix.sass('resources/beike/shop/default/css/app.scss', 'public/build/beike/shop/default/css/app.css');
mix.js('resources/beike/shop/default/js/app.js', 'public/build/beike/shop/default/js/app.js');

// 安装引导
mix.sass('beike/Installer/assets/scss/app.scss', 'public/install/css/app.css');


// design
mix.sass('resources/beike/admin/css/design/app.scss', 'public/build/beike/admin/css/design.css');

if (mix.inProduction()) {
    mix.version();
}

mix.browserSync({
    proxy: 'laravel.test'
});

// 前端热更新
if ( typeof process.env.MIX_PROXY != "undefined" || process.env.MIX_PROXY != '' ) {
  mix.browserSync({
    proxy: process.env.MIX_PROXY,   // apache或iis等代理地址
    port: 1001,
    notify: false,        // 刷新是否提示
    watchTask: true,
    open: false,
    host: process.env.MIX_HOST,  // 本机ip, 这样其他设备才可实时看到更新
  });
}
