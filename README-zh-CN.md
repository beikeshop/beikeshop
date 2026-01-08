<p align="center">
  <img src="https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/banner.jpg" alt="BeikeShop - 一个开源、易用的跨境电商平台" style="width:100%;max-width:900px;">
</p>

<p align="center">
  <u><a href="#快速开始">快速开始</a></u> |
  <u><a href="#环境要求">环境要求</a></u> |
  <u><a href="#系统亮点">系统亮点</a></u> |
  <u><a href="#页面预览">页面预览</a></u>
</p>

<div align="center">
  <a href="https://beikeshop.com/" target="_blank"><img src="https://img.shields.io/badge/BeikeShop-%23FF6F30" alt="logo"></a>
  <a href="https://www.php.net/" target="_blank"><img src="https://img.shields.io/badge/PHP-8.1%2B-%234F5B93?logoColor=%234F5B93&labelColor=%234F5B93" alt="logo"></a>
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

## BeikeShop 简介
BeikeShop 是一款基于 PHP 和 Laravel 开发的开源电子商务平台建站系统。它专为开发者和企业设计，系统易上手、容易 旨在通过闪电级的部署速度，帮助用户快速启动**自托管**的在线商店，并实现对代码、数据和基础设施的完全控制。BeikeShop 提供了一个高度直观、**开箱即用**的解决方案，使用户能够轻松高效地完成从安装到全功能商店上线的全过程。

该平台提供了全面的电商基础功能——包括产品管理、购物车、结账、支付、物流、多语言、多货币支持以及 **REST API**——是构建全球独立站的理想选择。

在架构设计上，BeikeShop 专为无缝的二次开发而生，采用了**模块化、事件驱动的架构**。通过利用强大的**钩子 (Hook) 与事件系统**，开发者可以开发插件并集成第三方服务，实现**非侵入式定制**，确保在不触动核心代码的情况下轻松进行维护和升级。

---

## 开发框架
- **编程语言**：PHP 8.1+
- **框架**：Laravel 10
- **前端**：Blade 模板, Vue.js
- **架构**：MVC, 模块化, 事件驱动
- **安全与逻辑**：中间件 (Middleware), Webhook 引擎
- **前端技术细分**：Blade / Vue / jQuery / Sass / Bootstrap
- **数据库**：MySQL, PostgreSQL
- **部署**：Docker

---

## 系统适用于
- PHP / Laravel 电商项目
- 定制化在线商店
- 跨境/国际电子商务
- 开源电商学习与研究开发


---

## 为什么选择 BeikeShop？

- **100% 开源：** 拥有代码和数据的完整所有权。
- **基于 Laravel：** 标准化开发，确保高性能与高安全性。
- **低维护成本：** 稳定、生产就绪且具备成本效益。
- **开发者友好：** 模块化架构支持快速定制。
- **轻量化：** 相比于臃肿的电商平台，BeikeShop 更加灵活敏捷。

---

## 在线演示

- **前台演示**：[https://demo.beikeshop.com/](https://demo.beikeshop.com/)
- **后台演示**：[https://demo.beikeshop.com/admin/](https://demo.beikeshop.com/admin/)
	- **账号**： demo@beikeshop.com
	- **密码**：demo

---

## 快速开始

BeikeShop 可以通过 **安装包**、**源码** 或 **Docker** 进行安装。

### 1. 安装包安装

1. 下载 BeikeShop：[https://beikeshop.com/download](https://beikeshop.com/download)

2. 上传并解压安装包至您的服务器。

3. 将 `public` 目录设置为 Web 根目录。

4. 在浏览器中访问站点，按照安装向导进行操作。

5. 安装指南：https://docs.beikeshop.com/install/bt.html

6. **升级**时，请覆盖文件（保留 `.env`）并运行：

  ```
  php artisan migrate
  ```


### 2. 源码安装

```
git clone https://github.com/beikeshop/beikeshop.git
cd beikeshop
composer install
cp .env.example .env
npm install
npm run prod
```

将 public 目录设置为 Web 根目录，并通过浏览器完成安装。

升级时：

```
git pull
composer install
php artisan migrate
```

### 3. Docker 安装

1. 安装 Docker & Docker Compose。

2. 克隆 Docker 环境配置：

  ```
  git clone git@gitee.com:beikeshop/docker.git
  ```

3. 创建网站目录：`mkdir www`

4. 配置环境并启动：

  ```
  cp env.example .env
  docker compose up -d
  ```


详细指南：https://docs.beikeshop.com/install/docker.html

---

## 📖 文档
- **官方网站**：[https://www.beikeshop.com](https://www.beikeshop.com/)
- **官方文档**：[https://docs.beikeshop.com](https://docs.beikeshop.com/)

---

# 环境要求
- **独立服务器**（不支持虚拟主机）
- **操作系统**：Ubuntu 22+ / CentOS 8.5
- **PHP**：8.2
- **数据库**：MySQL 5.7+
- **Web 服务器**：Nginx 1.10+ / Apache 2.4+
- **必须的 PHP 扩展**：BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML

---

# 系统亮点

- **0元起步**：BeikeShop 是真正的独立站，代码100%开源，数据信息100%自主可控
- **开源开放**：100% 开源，拥有源代码和数据的绝对所有权。
- **开箱即用**：专为快速部署设计，几分钟内即可完成从安装到商店上线。
- **基于 Laravel 10 框架**：遵循标准的 MVC 架构和行业最佳实践。
- **无佣金和手续费**：BeikeShop 没有佣金、年费或手续费，降低了建站成本
- **现代化 UI**：简洁、高转化率的前台界面和直观的后台管理面板，提供无缝的用户体验。
- **多语言多货币**：原生支持多语言和多货币，是国际在线商店的理想选择。
- **微内核和插件化**：采用微内核架构和插件化设计，使系统易维护 & 扩展
- **丰富的插件市场**：官方提供了丰富的插件市场，可以方便地购买需要的功能
- **事件与 Webhook**：通过 Webhook 和监听器 (Listeners) 实现灵活的事件驱动定制，确保无缝集成。
- **严格遵循 MVC 架构**：系统严格遵循 MVC 架构，提高了代码的可维护性和可扩展性
- **安全可靠**：具备基于角色的访问控制 (RBAC) 和严格的数据验证，安全性强。
- **开发者友好 & REST API**：代码整洁，提供完善的 REST API，支持无头电商 (Headless) 和移动端 App。
- **ERP 与集成就绪**：可轻松连接全球支付网关、物流系统以及主流 ERP 系统。
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

1. Fork 本仓库。

2. 创建新的功能分支 (Feature Branch)。

3. 提交您的修改。

4. 发起 Pull Request。

---

## 📄 开源协议

BeikeShop 是基于 **OSL-3.0** 协议发布的开源软件。

---

特别感谢
- **插件开发者**：撸串青年、老柳、Aegis、特仑叔、olives等
- **PR贡献者**：nilsir、what_村长、tanxiaoyong、Lucky、So、licy、老北、Teemo等
感谢你们参与到BeikeShop的开发中，共同为BeikeShop添砖加瓦！

感谢所有参与 BeikeShop 开发的贡献者，让项目变得更好！

QQ交流群：1033903216

---

注意

请保留原始版权声明。移除版权声明需要获得官方授权。

⭐ **如果您觉得 BeikeShop 对您有所帮助，请考虑在 GitHub 上为我们点亮一颗星星！**

---