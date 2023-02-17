<p align="center"><a href="https://beikeshop.com/" target="_blank"><img src="https://beikeshop.com/image/beike.svg" width="400"></a></p>

## BeikeShop

BeikeShop 一款开源好用的跨境电商系统

BeikeShop 是基于 Laravel 开发的一款开源商城系统，主要面向外贸，跨境行业提供的商品管理、订单管理、会员管理、支付、物流、系统管理等功能。


## 软件架构
PHP语言开发，基于 Laravel 框架，前端 Blade 模版 + Vue

## 环境要求
- PHP 8.0.2+
- MySQL 5.7+
- Apache httpd 2.4+ 或者 Nginx 1.10+

## PHP组件
- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## DEMO 演示
<a href="https://demo.beikeshop.com/" target="_blank">BeikeShop Demo</a>
<p>
  <a href="https://beikeshop.com/" target="_blank" style="border: 1px solid #eee; display: inline-block;"><img src="https://beikeshop.com/image/gif/demo.gif" width="500"></a>
</p>

## 安装教程(面向非开发者)
1. <a href="https://beikeshop.com/download" target="_blank">下载BeikeShop</a>
1. 上传到你的服务器并解压
1. 将解压文件夹下的 public 设置为网站根目录
1. 通过浏览器访问网站根据提示完成安装
1. <a href="https://docs.beikeshop.com/install/bt.html" target="_blank">BeikeShop详细安装指引</a>
1. 如需升级请下载最新版覆盖到服务器后网站根目录运行`composer install && php artisan migrate`

## 安装教程(面向开发者)
1. 打开命令行克隆代码 `git clone https://gitee.com/beikeshop/beikeshop.git`
1. 命令行进入 `beikeshop` 目录, 执行 `composer install` 安装第三方包
1. 接着执行 `cp .env.example .env` 创建配置文件
1. 接着执行 `npm install`（node 版本需16+） 以及 `npm run dev` 编译前端 js 和 css 文件
1. 将项目文件夹下的 `public` 设置为网站根目录
1. 通过浏览器访问网站, 根据提示完成安装
1. 如需升级请在服务器端网站根目录运行`git pull && composer install && php artisan migrate`

## 参与贡献
1. Fork 本仓库
1. 新建 feature-xxx 分支
1. 提交代码
1. 新建 Merge Request

## QQ交流群
1. 639108380
