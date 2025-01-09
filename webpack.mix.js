const mix = require('laravel-mix');

// 后台 scss/js
mix.sass('resources/beike/admin/css/bootstrap/bootstrap.scss', 'public/build/beike/admin/css/bootstrap.css');
mix.sass('resources/beike/admin/css/app.scss', 'public/build/beike/admin/css/app.css');
mix.js('resources/beike/admin/js/app.js', 'public/build/beike/admin/js/app.js');

// 安装引导
mix.sass('beike/Installer/assets/scss/app.scss', 'public/install/css/app.css');

// design
mix.sass('resources/beike/admin/css/design/app.scss', 'public/build/beike/admin/css/design.css');

// filemanager
mix.sass('resources/beike/admin/css/filemanager/app.scss', 'public/build/beike/admin/css/filemanager.css');

// 前端 default 模板
mix.sass('resources/beike/shop/default/css/bootstrap/bootstrap.scss', 'public/build/beike/shop/default/css/bootstrap.css');
mix.sass('resources/beike/shop/default/css/app.scss', 'public/build/beike/shop/default/css/app.css');
mix.js('resources/beike/shop/default/js/app.js', 'public/build/beike/shop/default/js/app.js');

/* 如果安装有某一个模板 ，要修改其 scss/js ，那么需要开启下面代码，并替换 fashion 为模版 code
mix.sass('resources/beike/shop/fashion/css/bootstrap/bootstrap.scss', 'public/build/beike/shop/fashion/css/bootstrap.css');
mix.sass('resources/beike/shop/fashion/css/app.scss', 'public/build/beike/shop/fashion/css/app.css');
mix.js('resources/beike/shop/fashion/js/app.js', 'public/build/beike/shop/fashion/js/app.js');
*/

/* 如果开发新模版，编译需要开启下面代码, 将 themeFileName 的值修改为你的模版文件名
const themeFileName =  'Fashion';
const themeCode = themeFileName.replace(/([A-Z])/g,"_$1").toLowerCase().replace(/^_/,'');

// 拷贝模版 blade 文件 到 themes 目录下
if (!mix.inProduction()) {
  mix.copy(`plugins/${themeFileName}/Themes`, 'themes');
}
// 编译模版 scss/js 到 public/build 下
mix.sass(`plugins/${themeFileName}/Resources/beike/shop/${themeCode}/css/bootstrap/bootstrap.scss`, `public/build/beike/shop/${themeCode}/css/bootstrap.css`);
mix.sass(`plugins/${themeFileName}/Resources/beike/shop/${themeCode}/css/app.scss`, `public/build/beike/shop/${themeCode}/css/app.css`);
mix.js(`plugins/${themeFileName}/Resources/beike/shop/${themeCode}/js/app.js`, `public/build/beike/shop/${themeCode}/js/app.js`);
*/

if (mix.inProduction()) {
  mix.version();
}