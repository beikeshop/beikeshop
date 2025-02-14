# BeikeShop - An Open Source, User-Friendly Cross-Border E-commerce System

**Introduction to BeikeShop**
BeikeShop is a globally leading open-source e-commerce system based on the Laravel framework, designed for the international trade and cross-border e-commerce industry.
The system is 100% open-source! It supports a wide range of practical features, including multi-language, multi-currency, payment, logistics, and member management, making it easy for foreign trade businesses to expand their independent online stores.



<a href="https://beikeshop.com/">
    <img src="https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-1.png">
</a>

**Note**: Please retain our company’s copyright information. Removal requires our company’s license authorization!

# BeikeShop System Highlights

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

# BeikeShop Store Preview Video
<p>
  <a href="https://demo.beikeshop.com/" target="_blank" style="border: 1px solid #eee; display: inline-block;">
    <img src="https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/demo.gif" style="width: 100%;">
</a>
</p>

<a href="https://demo.beikeshop.com/admin/login?admin_email=demo@beikeshop.com&admin_password=demo"
target="_blank">Click to Experience</a>
---

# Page Previews

1. **DIY Store Customization**
![页面展示1_商城DIY装修](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-3.png)
3. **Product List Page**
![页面展示2_商品列表页](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-4.png)
5. **Product Detail Page**
![页面展示3_商品详情页](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-5.png)
7. **Admin Product List**
![页面展示4_后台商品列表](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-6.png)
1. **Admin Feature Search**
![页面展示5_后台功能搜索](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-7.png)

---

# Related Services

If you have no technical background or want to quickly launch your independent store, you can purchase our hosting services!

![服务展示1_](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-8.png)
![服务展示2_](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-9.png)
If you want BeikeShop to meet your specific needs, we also offer 1-on-1 customization consulting services!
![服务展示3](https://raw.githubusercontent.com/beikeshop/beikeshop-resource/refs/heads/master/img/README-10.png)

---

# Software Architecture

- **Programming Language**: PHP 8.1
- **Framework**: Laravel 10
- **Frontend**: Blade Template + Vue.js

---

# Environment Requirements

- **Independent Server** (Virtual hosting not supported)
- **CentOS 7.0+** or **Ubuntu 20.04+**
- **PHP 8.1+**
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

[![建站流程](https://beikeshop.com/readme/README-11.png)](https://beikeshop.com/demo)
# Website Setup Process

**Installation Guide (For Non-developers)**

1.  <a href="https://beikeshop.cn/download" target="_blank">Download BeikeShop</a>
2. Upload to your server and unzip.
3. Set the `public` folder as the website root directory.
4. Access the website through your browser and follow the installation prompts.
5. <a href="https://docs.beikeshop.com/en/install/bt.html" target="_blank">BeikeShop Detailed Installation Guide</a>
6. If upgrading, download the latest version and overwrite on the server (make sure to keep the original `.env` file). Then, run `php artisan migrate` in the root directory.

**Installation Guide (For Developers)**

1. Open the command line and clone the repository:
    `git clone https://gitee.com/beikeshop/beikeshop.git`
2. Enter the BeikeShop directory and run `composer install` to install third-party packages.
3. Run `cp .env.example .env` to create the configuration file.
4. Run `npm install` (Node version 16+ required), followed by `npm run prod` to compile frontend JS and CSS files.
5. Set the `public` folder as the website root directory.
6. Access the website through your browser and follow the installation prompts.
7. For upgrades, run:
    `git pull && composer install && php artisan migrate` in the root directory.

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