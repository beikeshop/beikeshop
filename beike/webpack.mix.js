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

mix.setPublicPath('../public');

mix.sass('Resources/css/app.scss', 'Resources/build/css/shop/app.css');
mix.sass('Resources/css/bootstrap/bootstrap.scss', 'Resources/build/css/bootstrap.css');

mix.sass('Resources/css/admin/app.scss', 'Resources/build/css/admin/app.css');

if (mix.inProduction()) {
    mix.version();
}
