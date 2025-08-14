<p align="center">
  <img src="https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/banner.jpg" alt="BeikeShop - An Open Source, User-Friendly Cross-Border E-commerce Platform" style="width:100%;max-width:900px;">
</p>

<p align="center">
  <u><a href="#quick-start">Quick start</a></u> |
  <u><a href="#environment-requirements">Environment</a></u> |
  <u><a href="#key-features">Key Features</a></u> |
  <u><a href="#page-previews">Page Previews</a></u>
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
  <span>English</span> |
  <u><a href="README-zh-CN.md">简体中文</a></u>
</p>

**Introduction to BeikeShop**
BeikeShop is a globally leading open-source e-commerce Platform based on the Laravel framework, designed for the international trade and cross-border e-commerce industry.
The System is 100% open-source! It supports a wide range of practical features, including multi-language, multi-currency, payment, logistics, and member management, making it easy for foreign trade businesses to expand their independent online stores.

---

# Framework

- **Programming Language**: PHP 8.3
- **Framework**: Laravel 10
- **Frontend**: Blade Template + Vue.js

---
# Live Demo

Frontend Demo：[https://demo.beikeshop.com/](https://demo.beikeshop.com/)

Backend Demo：[https://demo.beikeshop.com/admin/](https://demo.beikeshop.com/admin/login?admin_email=demo@beikeshop.com&admin_password=demo)

---

# Quick start

**Ⅰ. Package Installation**
1.  <a href="https://beikeshop.com/download" target="_blank">Download BeikeShop</a>
2. Upload to your server and unzip.
3. Set the `public` folder as the website root directory.
4. Access the website through your browser and follow the installation prompts.
5. <a href="https://docs.beikeshop.com/en/install/bt.html" target="_blank">BeikeShop Detailed Installation Guide</a>
6. If upgrading, download the latest version and overwrite on the server (make sure to keep the original `.env` file). Then, run `php artisan migrate` in the root directory.

**Ⅱ. Source Code Installation**
1. Open the command line and clone the repository:
    `git clone https://github.com/beikeshop/beikeshop.git`
2. Enter the BeikeShop directory and run `composer install` to install third-party packages.
3. Run `cp .env.example .env` to create the configuration file.
4. Run `npm install` (Node version 16+ required), followed by `npm run prod` to compile frontend JS and CSS files.
5. Set the `public` folder as the website root directory.
6. Access the website through your browser and follow the installation prompts.
7. For upgrades, run:
    `git pull && composer install && php artisan migrate` in the root directory.

**Ⅲ. Docker Installation**
1. Install Docker and Compose locally or on the server, refer to the installation guide here: [https://docs.docker.com/engine/install/](https://docs.docker.com/engine/install/)
2. Execute the command: `git clone git@gitee.com:beikeshop/docker.git`
3. Create a new directory named `www` as the website directory: `mkdir www`（See details: [https://docs.beikeshop.com/en/install/docker.html](https://docs.beikeshop.com/en/install/docker.html)）
4. Enter the `docker` directory and create the configuration file based on the template: `cp env.example .env`
5. Modify the `.env` file and `docker-compose` as needed, then run the command: `docker compose up -d`

---

# Environment Requirements

- **Independent Server** (Virtual hosting not supported)
- **CentOS 7.0+** or **Ubuntu 20.04+**
- **PHP 8.3+**
- **MySQL 5.7+**
- **Apache httpd 2.4+** or **Nginx 1.10+**

**Required PHP Extensions**:
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

# Key Features

- **Zero Start-Up Cost**: BeikeShop is a true independent platform, 100% open-source, with 100% control over data.
- **Built on Laravel 10**: Developed using the Laravel 10 framework, offering solid framework support.
- **No Commission or Fees**: BeikeShop has no commissions, annual fees, or transaction fees, reducing the cost of setting up a website.
- **Microkernel & Modular Design**: With a microkernel architecture and modular design, BeikeShop is easy to maintain and extend.
- **Clear Code Structure & Format**: The system adopts a clear layered code structure with standardized formatting, improving readability and maintainability.
- **Event System for Hook Functionality**: Flexible hook functionality is achieved through the event system, making customization and extension easier.
- **Rich Plugin Marketplace**: A wide range of plugins are available in the official marketplace, allowing easy acquisition of required features.
- **Multi-language and Multi-currency Support**: The system supports multiple languages and currencies, making it ideal for users across different regions and countries.
- **Attractive Interface & Visual Customization**: BeikeShop features an attractive design with visual customization options, ensuring an excellent user experience.
- **Strict MVC Architecture**: The system strictly follows the MVC architecture, enhancing maintainability and scalability.
- **Easy Operation & Quick Setup**: BeikeShop is easy to operate and set up, allowing rapid deployment.
![系统亮点](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-2.png)


---

# Store Preview
<p>
  <a href="https://demo.beikeshop.com/" target="_blank" style="border: 1px solid #eee; display: inline-block;">
    <img src="https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/demo.gif" style="width: 120%;">
</a>
</p>

# Page Previews

1. **DIY Store Customization**

  <video src="https://github.com/user-attachments/assets/22c646ec-696e-4e33-ab26-545f0ee96ee5" controls="controls" muted="muted" class="d-block rounded-bottom-2 border-top width-fit" width="100%">
  </video>

2. **Product Detail Page**
![页面展示3_商品详情页](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-5.png)
3. **Admin Product List**
![页面展示4_后台商品列表](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-6.png)



---
# Contributing

1. Fork this repository.
2. Create a new `feature-xxx` branch.
3. Submit your code changes.
4. Create a Merge Request.
---

# Special Thanks

- **Plugin Developers**: Lu Chuan Youth, Lao Liu, Aegis, Te̶lon̶ Uncle, Olives, etc.
- **PR Contributors**: nilsir, what_village_head, tanxiaoyong, Lucky, So, licy, Lao Bei, Teemo, etc.

We thank all contributors who participated in the development of BeikeShop, helping to make it better!

---
**Note！** Please retain our company’s copyright information. Removal requires our company’s license authorization!