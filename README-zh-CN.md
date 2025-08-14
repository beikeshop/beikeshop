<p align="center">
  <img src="https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/banner.jpg" alt="BeikeShop - 一个开源、易用的跨境电商平台" style="width:100%;max-width:900px;">
</p>

<p align="center">
  <u><a href="#快速开始">快速开始</a></u> |
  <u><a href="#环境要求">环境要求</a></u> |
  <u><a href="#核心特性">核心特性</a></u> |
  <u><a href="#页面预览">页面预览</a></u>
</p>

<div align="center">
  <a href="https://beikeshop.com/" target="_blank"><img src="https://img.shields.io/badge/BeikeShop-%23FF6F30" alt="logo"></a>
  <a href="https://www.php.net/" target="_blank"><img src="https://img.shields.io/badge/PHP-8.3%2B-%234F5B93?logoColor=%234F5B93&labelColor=%234F5B93" alt="logo"></a>
  <a href="https://laravel.com/" target="_blank"><img src="https://img.shields.io/badge/-Laravel%2010-%23FF2D20?logo=laravel&logoColor=%23fff&labelColor=%23FF7467" alt="logo"></a>
</div>
<p align="center">
  <a href="https://beikeshop.com/download" target="_blank"><img src="https://img.shields.io/badge/release-v1.5.6-%234B79B6?labelColor=%234B79B6" alt="logo"></a>
  <a href="https://beikeshop.com/demo" target="_blank"><img src="https://img.shields.io/badge/Demo-available-%2363B95C?labelColor=%23959494" alt="logo"></a>
  <a href="https://beikeshop.com/download" target="_blank"><img src="https://img.shields.io/badge/Downloads-163k-%23ED9017?logoColor=%23fff&labelColor=%23c57e37" alt="logo"></a>
</p>

<p align="center">
  <u><a href="README.md">English</a></u> |
  <span>简体中文</span>
</p>

**BeikeShop 简介**
BeikeShop 是一款全球领先的开源电商平台，基于 Laravel 框架开发，专为国际贸易和跨境电商行业打造。
系统 100% 开源！支持多语言、多币种、支付、物流、会员管理等丰富实用功能，助力外贸企业轻松拓展独立站。

---

# 框架

- **开发语言**：PHP 8.3
- **框架**：Laravel 10
- **前端**：Blade 模板 + Vue.js

---

# 在线演示

前台演示：[https://demo.beikeshop.com/](https://demo.beikeshop.com/)

后台演示：[https://demo.beikeshop.com/admin/](https://demo.beikeshop.com/admin/login?admin_email=demo@beikeshop.com&admin_password=demo)

---

# 快速开始

**Ⅰ. 快速安装**（安装包安装）
1.  <a href="https://beikeshop.com/download" target="_blank">下载 BeikeShop</a>
2. 上传到服务器并解压
3. 设置 `public` 文件夹为网站根目录
4. 通过浏览器访问网站，按提示完成安装
5. <a href="https://docs.beikeshop.com/zh/install/bt.html" target="_blank">详细安装教程</a>
6. 升级时，下载最新版覆盖服务器（保留原 `.env` 文件），然后在根目录运行 `php artisan migrate`

**Ⅱ. 源码安装**
1. 打开命令行，克隆仓库：
    `git clone https://github.com/beikeshop/beikeshop.git`
2. 进入 BeikeShop 目录，运行 `composer install` 安装依赖
3. 运行 `cp .env.example .env` 创建配置文件
4. 运行 `npm install`（需 Node 16+），再运行 `npm run prod` 编译前端资源
5. 设置 `public` 文件夹为网站根目录
6. 通过浏览器访问网站，按提示完成安装
7. 升级时，在根目录运行：
    `git pull && composer install && php artisan migrate`

**Ⅲ. Docker 安装**
1. 本地或服务器安装 Docker 和 Compose，参考：[https://docs.docker.com/engine/install/](https://docs.docker.com/engine/install/)
2. 执行命令：`git clone git@gitee.com:beikeshop/docker.git`
3. 新建 `www` 目录作为网站目录：`mkdir www`（详见：[https://docs.beikeshop.com/zh/install/docker.html](https://docs.beikeshop.com/zh/install/docker.html)）
4. 进入 `docker` 目录，按模板创建配置文件：`cp env.example .env`
5. 按需修改 `.env` 和 `docker-compose`，然后运行：`docker compose up -d`

---

# 环境要求

- **独立服务器**（不支持虚拟主机）
- **CentOS 7.0+** 或 **Ubuntu 20.04+**
- **PHP 8.3+**
- **MySQL 5.7+**
- **Apache httpd 2.4+** 或 **Nginx 1.10+**

**必需 PHP 扩展**：
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PCRE
- PDO
- Tokenizer
- XML

---

# BeikeShop系统亮点

- **0元起步**：BeikeShop 是真正的独立站，代码100%开源，数据信息100%自主可控
- **基于 Laravel 10 框架**：BeikeShop 使用 Laravel 10 框架进行开发，拥有成熟的框架支持
- **无佣金和手续费**：BeikeShop 没有佣金、年费或手续费，降低了建站成本
- **微内核和插件化**：采用微内核架构和插件化设计，使系统易维护 & 扩展
- **清晰的代码分层和格式规范**：系统代码采用分层清晰、格式规范的结构，提高代码的可读性和可维护性
- **Event 机制实现 Hook 功能**：通过 Event 机制实现了灵活的 Hook 功能，方便扩展和定制化开发
- **丰富的插件市场**：官方提供了丰富的插件市场，可以方便地购买需要的功能
- **多语言和多货币支持**：系统支持多语言和多货币，方便面向不同地区和国家的用户
- **界面美观和可视化装修**：系统界面设计美观，支持可视化装修，提供良好的用户体验
- **严格遵循 MVC 架构**：系统严格遵循 MVC 架构，提高了代码的可维护性和可扩展性
- **操作简单易上手**：BeikeShop 操作简单，易于上手，可以快速上线使用
![系统亮点](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-2-zh-CN.png)

---

# 店铺预览
<p>
  <a href="https://demo.beikeshop.com/" target="_blank" style="border: 1px solid #eee; display: inline-block;">
    <img src="https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/demo.gif" style="width: 120%;">
</a>
</p>

# 页面预览

1. **DIY 店铺装修**

  <video src="https://github.com/user-attachments/assets/22c646ec-696e-4e33-ab26-545f0ee96ee5" controls="controls" muted="muted" class="d-block rounded-bottom-2 border-top width-fit" width="100%">
  </video>

2. **商品详情页**
![页面展示3_商品详情页](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-5.png)
3. **后台商品列表**
![页面展示4_后台商品列表](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-6.png)

---

# 参与贡献

1. Fork 本仓库
2. 新建 `feature-xxx` 分支
3. 提交代码
4. 创建 Merge Request

---

# 特别感谢

**插件开发者**：撸串青年、老柳、Aegis、特̶仑̶叔、olives等
**PR贡献者**：nilsir、what_村长、tanxiaoyong、Lucky、So、licy、老北、Teemo等 感谢你们参与到BeikeShop的开发中，共同为BeikeShop添砖加瓦！

感谢所有参与 BeikeShop 开发的贡献者，让项目变得更好！
### QQ交流群：1033903216

---

**注意！** 请保留我司版权信息，如需移除请联系授权！