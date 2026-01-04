<p align="center">
  <img src="https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/banner.jpg" alt="BeikeShop - An Open Source, User-Friendly Cross-Border E-commerce Platform" style="width:100%;max-width:900px;">
</p>

<p align="center">
  <u><a href="#quick-start">Quick start</a></u> |
  <u><a href="#environment-requirements">Environment</a></u> |
  <u><a href="#key-features">Key Features</a></u> |
  <u><a href="#page-previews">Page Previews</a></u> |
  <u><a href="#live-demo">Demo</a></u>
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

## BeikeShop — Open Source PHP & Laravel Ecommerce Platform

BeikeShop is an open-source ecommerce platform built on PHP and Laravel, designed for developers and businesses to launch **self-hosted** online stores with lightning-fast deployment and full control over code, data, and infrastructure. It offers a highly intuitive, **out-of-the-box** solution that enables users to move from installation to a fully functional store with ease and efficiency.

The platform provides a comprehensive ecommerce foundation—including product management, shopping cart, checkout, payments, shipping, multi-language, multi-currency support, and **REST APIs**—making it the ideal choice for global independent online stores.

Engineered for seamless secondary development, BeikeShop follows a **modular, event-driven architecture**. By utilizing a robust **Hook and Event-based system**, developers can extend features, build plugins, and integrate third-party services through **non-intrusive customization**, ensuring the core code remains untouched for easy maintainability and upgrades.


---

## Tech Stack

- **Language**: PHP 8.1+
- **Framework**: Laravel 10
- **Frontend**: Blade Templates, Vue.js
- **Architecture**: MVC, Modular, Event-driven
- **Security & Logic**:Middleware, Webhook Engine
- **Frontend:** Blade / vue / jQuery / sass / Bootstrap
- **Database**: MySQL, PostgreSQL
- **Deployment:** Docker

* * *

## Use Cases

- PHP / Laravel ecommerce projects
-  Custom online stores
- International ecommerce
- Open‑source ecommerce learning and development

---
## Why BeikeShop?

- **100% Open-Source:** Full ownership of code and data.

- **Laravel-Based:** Standardized for performance and security.

- **Low Maintenance:** Stable, production-ready, and cost-effective.

- **Developer-Friendly:** Modular architecture for fast customization.

- **Lightweight:** Agile alternative to bloated ecommerce platforms.

---
## Live Demo

- **Frontend Demo**: https://demo.beikeshop.com/
- **Admin Demo**: https://demo.beikeshop.com/admin/
- Email: demo@beikeshop.com
- Password: demo

---

## Quick Start

BeikeShop can be installed via **package distribution**, **source code**, or **Docker**.

### I. Package Installation

1. Download BeikeShop: https://beikeshop.com/download

2. Upload and unzip the package on your server.

3. Set the `public` directory as the web root.

4. Visit the site in your browser and follow the installation wizard.

5. Installation guide: https://docs.beikeshop.com/en/install/bt.html

6. For upgrades, overwrite files (keep `.env`) and run:

   ```bash
   php artisan migrate
   ```

### II. Source Code Installation

```bash
git clone https://github.com/beikeshop/beikeshop.git
cd beikeshop
composer install
cp .env.example .env
npm install
npm run prod
```

Set the `public` directory as the web root and complete installation via browser.

For upgrades:

```bash
git pull
composer install
php artisan migrate
```

### III. Docker Installation

1. Install Docker & Docker Compose
    [https://docs.docker.com/engine/install/](https://docs.docker.com/engine/install/)

2. Clone Docker environment:

   ```bash
   git clone git@gitee.com:beikeshop/docker.git
   ```

3. Create website directory:

   ```bash
   mkdir www
   ```

4. Configure environment:

   ```bash
   cp env.example .env
   docker compose up -d
   ```

Detailed guide: [https://docs.beikeshop.com/en/install/docker.html](https://docs.beikeshop.com/en/install/docker.html)

---

## Documentation

* Official Website: [https://www.beikeshop.com](https://www.beikeshop.com)

* Documentation: [https://docs.beikeshop.com/en/](https://docs.beikeshop.com/en/)


---
## Environment Requirements

- **Independent Server** (shared hosting not supported)

- **OS**: Ubuntu 22+ / CentOS 8.5

- **PHP**: 8.2

- **Database**: MySQL 5.7+

- **Web Server**: Nginx 1.10+ / Apache 2.4+

### Required PHP Extensions

BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML

---


## Key Features

- **Open Source:** 100% open-source with full ownership of source code and data.

- **Easy to Use:** Designed for rapid deployment, allowing you to go from installation to a live store in minutes.

- **Laravel Framework:** Built on Laravel 10+, adhering to standard MVC architecture and industry best practices.

- **Modern UI:** Clean, high-conversion storefront and an intuitive admin dashboard for a seamless user experience.

- **Complete Workflow:** Out-of-the-box support for products, cart, checkout, payments, shipping, and customer management.

- **Global Commerce:** Native multi-language and multi-currency support, ideal for international online stores.

- **Modular Architecture:** Decoupled design allowing functional expansion via plugins without modifying the core.

- **Events & Webhooks:** Flexible event-driven customization through Webhooks and Listeners for seamless integration.

- **Secure & Reliable:** Robust security with role-based access control (RBAC) and strict data validation.

- **Developer-Friendly & REST APIs:** Clean codebase with comprehensive REST APIs for headless commerce and mobile apps.

- **ERP & Integration Ready:** Easy connection with global payment gateways, logistics, and mainstream ERP systems.

![System Overview](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-2.png)


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
## Contributing

1. Fork this repository.

2. Create a new feature branch.

3. Commit your changes.

4. Submit a Pull Request.


---
## License

BeikeShop is open‑source software licensed under the [![License: OSL-3.0](https://img.shields.io/badge/License-OSL--3.0-blue.svg)](https://opensource.org/licenses/OSL-3.0)


---

## Special Thanks

- **Plugin Developers**: Lu Chuan Youth, Lao Liu, Aegis, Telon Uncle, Olives, and others.

- **Contributors**: nilsir, what_village_head, tanxiaoyong, Lucky, So, licy, Lao Bei, Teemo, and more.

Thank you to everyone who contributes to making BeikeShop better.

---

**Note**
Please retain the original copyright notice.
Removal requires official authorization.

⭐ If you find BeikeShop useful, please consider giving it a star on GitHub!
