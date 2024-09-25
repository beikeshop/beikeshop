
<img height=70 src="https://beikeshop.com/image/beike.svg" >

#  &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;一款开源好用的跨境电商系统



## BeikeShop介绍
BeikeShop是一款基于 Laravel 框架开发的全球领先的开源商城系统，面向外贸/跨境电商行业。
系统100%开源！系统支持前台/后台多语言、多货币、支付、物流、会员管理等实用功能，助力外贸业务轻松扩展独立站业务
<br>


<a href="https://beikeshop.com/">
    <img src="https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-1.png">
</a>

### <font>**注意：请保留我公司版权信息，如要移除，需要我公司license授权！**</font>


## BeikeShop系统亮点
- 0元起步：BeikeShop 是真正的独立站，代码100%开源，数据信息100%自主可控
- 基于 Laravel 10 框架：BeikeShop 使用 Laravel 10 框架进行开发，拥有成熟的框架支持
- 无佣金和手续费：BeikeShop 没有佣金、年费或手续费，降低了建站成本
- 微内核和插件化：采用微内核架构和插件化设计，使系统易维护 & 扩展
- 清晰的代码分层和格式规范：系统代码采用分层清晰、格式规范的结构，提高代码的可读性和可维护性
- Event 机制实现 Hook 功能：通过 Event 机制实现了灵活的 Hook 功能，方便扩展和定制化开发
- 丰富的插件市场：官方提供了丰富的插件市场，可以方便地购买需要的功能
- 多语言和多货币支持：系统支持多语言和多货币，方便面向不同地区和国家的用户
- 界面美观和可视化装修：系统界面设计美观，支持可视化装修，提供良好的用户体验
- 严格遵循 MVC 架构：系统严格遵循 MVC 架构，提高了代码的可维护性和可扩展性
- 操作简单易上手：BeikeShop 操作简单，易于上手，可以快速上线使用

![系统亮点](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-2.png)

<br>

## BeikeShop 商城预览视频

<p>
  <a href="https://demo.beikeshop.com/" target="_blank" style="border: 1px solid #eee; display: inline-block;width: 100%">
    <img src="https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/demo.gif" style="width: 100%;">
</a>
</p>


<a href="https://demo.beikeshop.com/admin/login?admin_email=demo@beikeshop.com&admin_password=demo"
target="_blank">点击立刻体验：BeikeShop演示站</a>


<br>
<br>




















## 页面展示

![页面展示1_商城DIY装修](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-3.png)
![页面展示2_商品列表页](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-4.png)
![页面展示3_商品详情页](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-5.png)
![页面展示4_后台商品列表](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-6.png)
![页面展示5_后台功能搜索](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-7.png)


## 相关服务
如果您没有任何技术基础，或想要快速的拥有自己的独立站，可以购买我们的托管服务！
![服务展示1_](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-8.png)
![服务展示2_](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-9.png)

如果希望使用 BeikeShop 满足您的更多需求，我们还提供1对1的定制咨询服务！

[![服务展示3](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-10.png)](https://wpa.qq.com/msgrd?v=3&uin=9358972&site=qq&menu=yes)

## 软件架构
使用语言 PHP 8.1
基于 Laravel 10 框架
前端 Blade 模板 + Vue

## 环境要求
- 独立服务器(不能使用虚拟空间)
- CentOS 7.0+ 或 Ubuntu 20.04+
- PHP 8.1+
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




[![建站流程](https://beikeshop.com/readme/README-11.png)](https://beikeshop.cn/demo)


## 安装教程(面向非开发者)
1. <a href="https://beikeshop.cn/download" target="_blank">下载BeikeShop</a>
1. 上传到你的服务器并解压
1. 将解压文件夹下的 public 设置为网站根目录
1. 通过浏览器访问网站根据提示完成安装
1. <a href="https://docs.beikeshop.com/install/bt.html" target="_blank">BeikeShop详细安装指引</a>
1. 如需升级, 请下载最新版覆盖到服务器(必须保留原有.env文件), 然后在网站根目录运行`php artisan migrate`

## 安装教程(面向开发者)
1. 打开命令行克隆代码 `git clone https://gitee.com/beikeshop/beikeshop.git`
1. 命令行进入 `beikeshop` 目录, 执行 `composer install` 安装第三方包
1. 接着执行 `cp .env.example .env` 创建配置文件
1. 接着执行 `npm install`（node 版本需16+） 以及 `npm run prod` 编译前端 js 和 css 文件
1. 将项目文件夹下的 `public` 设置为网站根目录
1. 通过浏览器访问网站, 根据提示完成安装
1. 如需升级请在服务器端网站根目录运行`git pull && composer install && php artisan migrate`

## 参与贡献
1. Fork 本仓库
1. 新建 feature-xxx 分支
1. 提交代码
1. 新建 Merge Request

## 特别鸣谢
插件开发者：撸串青年、老柳、Aegis、特̶仑̶叔、olives等
PR贡献者：nilsir、what_村长、tanxiaoyong、Lucky、So、licy、老北、Teemo等
感谢你们参与到BeikeShop的开发中，共同为BeikeShop添砖加瓦！

## QQ交流群：
群1: 639108380

