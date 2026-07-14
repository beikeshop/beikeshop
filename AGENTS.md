# AGENTS.md — AI Agent Installation Guide

## Overview

This file provides standardized installation instructions for AI Agents to automate BeikeShop deployment.

## System Architecture

BeikeShop is a modern e-commerce platform built on Laravel 10, featuring a modular architecture with clear separation of concerns.

### Core Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Web Server Layer                      │
│              (Nginx/Apache + PHP 8.2)                   │
└────────────────┬────────────────────────────────────────┘
                 │
┌────────────────▼────────────────────────────────────────┐
│                  Laravel Application                     │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐ │
│  │   Admin      │  │    Shop      │  │   Plugins    │ │
│  │  (Backend)   │  │  (Frontend)  │  │  (Extension) │ │
│  └──────────────┘  └──────────────┘  └──────────────┘ │
└────────────────┬────────────────────────────────────────┘
                 │
┌────────────────▼────────────────────────────────────────┐
│                  Service Layer                           │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐ │
│  │  Services    │  │ Repositories │  │   Models     │ │
│  │  (Business)  │  │   (Data)     │  │  (ORM)       │ │
│  └──────────────┘  └──────────────┘  └──────────────┘ │
└────────────────┬────────────────────────────────────────┘
                 │
┌────────────────▼────────────────────────────────────────┐
│                  Database Layer                          │
│              (MySQL 8.0 / PostgreSQL)                    │
└─────────────────────────────────────────────────────────┘
```

### Design Patterns

- **Repository Pattern**: Data access abstraction in `beike/Repositories/`
- **Service Layer**: Business logic in `beike/Services/`
- **Plugin System**: Extensible architecture in `beike/Plugin/`
- **Theme System**: Customizable frontend in `themes/`

## Project Structure

### Top-Level Directories

```
beikeshop/
├── app/                 # Laravel application code
├── beike/               # BeikeShop core business logic
├── bootstrap/           # Laravel bootstrap files
├── config/              # Application configuration
├── database/            # Migrations, seeds, factories
├── plugins/             # Third-party plugins directory
├── public/              # Web root (index.php, assets)
├── resources/           # Views, assets, data, and language files
├── routes/              # Route definitions
├── storage/             # Logs, cache, uploads
├── tests/               # PHPUnit tests
├── themes/              # Frontend theme templates
└── vendor/              # Composer dependencies
```

### BeikeShop Core (`beike/`)

The `beike/` directory contains all BeikeShop-specific business logic:

| Directory | Description |
|-----------|-------------|
| `Admin/` | Backend admin panel controllers, views, and logic |
| `AdminAPI/` | Admin API controllers, routes, and providers |
| `Shop/` | Frontend storefront controllers and views |
| `Models/` | Eloquent models (Product, Order, Customer, etc.) |
| `Repositories/` | Data access layer (DB queries and relationships) |
| `Services/` | Business logic services (cart, checkout, email, etc.) |
| `Config/` | BeikeShop-specific configuration |
| `Console/` | Artisan commands |
| `Installer/` | Installation wizard logic |
| `Hook/` | Hook system and extension points |
| `Fields/` | Field definitions and form metadata |
| `Facades/` | BeikeShop facade classes |
| `Libraries/` | Shared utility classes |
| `Mail/` | Mail classes and templates integration |
| `Notifications/` | Notification classes |
| `Plugin/` | Plugin system core (loader, hooks, lifecycle) |

### Resources Structure (`resources/`)

Application resources are organized by runtime area:

| Directory | Description |
|-----------|-------------|
| `beike/admin/` | Admin panel frontend assets and views |
| `beike/shop/` | Shop frontend assets and default theme resources |
| `data/` | Seed and static data files |
| `js/` | Shared frontend JavaScript entry points |
| `lang/` | Multi-language files for admin and shop |
| `views/` | Laravel views and vendor-published views |

### Theme Structure (`themes/default/`)

Frontend templates organized by feature:

| Directory | Description |
|-----------|-------------|
| `account/` | Customer account pages (login, register, profile) |
| `cart/` | Shopping cart views |
| `checkout/` | Checkout process views |
| `brand/` | Brand listing pages |
| `components/` | Reusable UI components |
| `design/` | Visual page builder components |
| `errors/` | Error pages (404, 500, etc.) |
| `layout/` | Base layout templates |
| `mails/` | Email templates |
| `page_categories/` | Page category templates |
| `pages/` | Static pages (about, contact, etc.) |
| `product/` | Product listing and detail templates |
| `shared/` | Shared partials (header, footer, sidebar) |

### Plugin System

Plugins extend BeikeShop functionality without modifying core code:

```
plugins/
└── my-plugin/
    ├── Bootstrap.php       # Plugin entry point
    ├── composer.json       # Plugin dependencies
    ├── Routes/             # Plugin routes
    ├── Controllers/        # Plugin controllers
    ├── Models/             # Plugin models
    ├── Services/           # Plugin services
    ├── Static/             # Plugin static assets
    ├── Views/              # Plugin views
    └── Lang/               # Plugin translations
```

### Plugin Artisan Commands

BeikeShop includes plugin scaffolding commands for creating and maintaining plugin files:

```bash
# Plugin lifecycle
php artisan plugin:make
php artisan plugin:list
php artisan plugin:use
php artisan plugin:unuse
php artisan plugin:delete
php artisan plugin:zip

# Plugin code generators
php artisan plugin:make-action
php artisan plugin:make-aspect
php artisan plugin:make-channel
php artisan plugin:make-command
php artisan plugin:make-component
php artisan plugin:make-component-view
php artisan plugin:make-controller
php artisan plugin:make-enum
php artisan plugin:make-event
php artisan plugin:make-event-provider
php artisan plugin:make-exception
php artisan plugin:make-factory
php artisan plugin:make-helper
php artisan plugin:make-interface
php artisan plugin:make-job
php artisan plugin:make-listener
php artisan plugin:make-mail
php artisan plugin:make-middleware
php artisan plugin:make-migration
php artisan plugin:make-model
php artisan plugin:make-notification
php artisan plugin:make-observer
php artisan plugin:make-policy
php artisan plugin:make-provider
php artisan plugin:make-request
php artisan plugin:make-resource
php artisan plugin:make-service
php artisan plugin:make-trait
php artisan plugin:make-view

# Plugin model inspection
php artisan plugin:model-show
```

## Environment Requirements

| Component | Minimum Version | Recommended | Notes |
|-----------|----------------|-------------|-------|
| **PHP** | 8.2 | 8.2+ | Required extensions: pdo_mysql, mbstring, gd, zip, bcmath, exif, pcntl, opcache |
| **MySQL** | 8.0 | 8.0+ | Also supports PostgreSQL 14+ |
| **Nginx** | 1.20 | 1.24+ | Required modules: rewrite, fastcgi |
| **Apache** | 2.4 | 2.4+ | Required modules: mod_rewrite, mod_headers |
| **Composer** | 2.0 | 2.5+ | PHP dependency manager |
| **Node.js** | 16 | 18+ | Only needed for theme development |
| **Docker** | 20.10 | 24+ | Only needed for Docker deployment |
| **Docker Compose** | 2.0 | 2.20+ | V2 syntax (`docker compose`) |

## Key Features

- **Multi-language**: Built-in support for 20+ languages
- **Multi-currency**: Dynamic currency switching
- **Responsive Design**: Mobile-first theme system
- **Plugin Marketplace**: Extensible via plugins
- **Visual Page Builder**: Drag-and-drop page design
- **RESTful API**: Headless commerce support
- **SEO Optimized**: Clean URLs and meta tags
- **Payment Gateways**: PayPal, Stripe, and more

## Installation Methods

### Method 1: CLI Command Installation (Recommended)

```bash
# Basic installation (default configuration)
php artisan beikeshop:install

# Custom configuration
php artisan beikeshop:install \
    --domain=shop.example.com \
    --db-host=localhost \
    --db-name=beikeshop \
    --db-user=beikeshop \
    --db-password=*** \
    --admin-email=*** \
    --admin-password=*** \
    --force
```

### Method 2: One-Click Script Installation

```bash
# Auto-detect environment (BT Panel/Docker/Manual LNMP)
chmod +x install.sh
./install.sh

# Custom configuration
PROJECT_NAME=myshop \
DOMAIN=shop.example.com \
DB_PASSWORD=*** \
./install.sh
```

### Method 3: Docker Deployment

```bash
# Clone repository
git clone https://github.com/beikeshop/beikeshop-installer.git
cd beikeshop-installer

# Configure environment
cp .env.example .env
vim .env

# Start services (Nginx + PHP by default)
docker compose up -d

# Execute installation
docker compose exec web php artisan beikeshop:install --force

# Or use Apache instead of Nginx
docker compose --profile apache up -d
```

### Docker Dual-Mode Deployment

The installer supports two web server modes via Docker Compose profiles:

| Mode | Command | Container | Description |
|------|---------|-----------|-------------|
| **Nginx** (default) | `docker compose --profile nginx up -d` | `${PROJECT_NAME}-nginx` | Nginx + PHP-FPM, high performance |
| **Apache** | `docker compose --profile apache up -d` | `${PROJECT_NAME}-apache` | Apache + mod_php, compatible with `.htaccess` rules |

**How it works:**
- Single `docker-compose.yml` file with profiles for each web server
- MySQL service is always started (no profile required)
- Choose web server via `--profile nginx` or `--profile apache`
- MySQL service is shared across both modes

**Switching modes:**
```bash
# Stop current mode
docker compose down

# Switch to Apache
docker compose --profile apache up -d

# Switch back to Nginx
docker compose up -d
```

## Environment Detection Logic

The installation script automatically detects the runtime environment:

1. **BT Panel**: Checks for `/www/server/panel` directory
2. **Docker**: Checks for `docker` and `docker-compose` commands
3. **Manual LNMP**: Checks for `nginx`, `mysql`, and `php` commands

## Installation Steps (AI Agent Execution Flow)

### Step 1: Check Environment

```bash
# Check Docker
docker --version
docker info

# Check port availability
lsof -i :80
lsof -i :3306
```

### Step 2: Create Project Structure

```bash
PROJECT_NAME="beikeshop"
PROJECT_DIR="/www/$PROJECT_NAME"

mkdir -p "$PROJECT_DIR"/{docker/nginx,docker/apache,logs/nginx,logs/apache,logs/mysql,www}
```

### Step 3: Generate Configuration Files

Required files:
- `docker-compose.yml` - Docker Compose configuration with profiles (nginx/apache)
- `docker/nginx/Dockerfile` - Nginx + PHP-FPM all-in-one image
- `docker/apache/Dockerfile` - Apache + mod_php image (optional)
- `.env` - Environment variables

### Step 4: Deploy Source Code

```bash
# Extract BeikeShop to www directory
unzip -q BeikeShop-v1.6.0.zip -d "$PROJECT_DIR/www/"
cd "$PROJECT_DIR/www"
```

### Step 5: Start Services

```bash
cd "$PROJECT_DIR"
docker compose up -d --build

# Wait for services
sleep 5
docker compose ps

# Or use Apache instead
docker compose --profile apache up -d --build
```

### Step 6: Configure Environment

```bash
# Generate .env file
docker exec ${PROJECT_NAME}-nginx bash -c "cat > /var/www/html/.env << 'EOF'
APP_NAME=BeikeShop
APP_ENV=local
APP_KEY=base64:$(openssl rand -base64 32)
APP_DEBUG=false
APP_URL=http://127.0.0.1
APP_TIMEZONE=PRC

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=app
DB_USERNAME=app
DB_PASSWORD=***

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync
EOF"

# Clear config cache
docker exec ${PROJECT_NAME}-nginx php artisan config:clear
```

### Step 7: Database Migration

```bash
# Run migrations
docker exec ${PROJECT_NAME}-nginx php artisan migrate --force

# Seed database
docker exec ${PROJECT_NAME}-nginx php artisan db:seed --force
```

### Step 8: Create Admin User

```bash
docker exec ${PROJECT_NAME}-nginx php artisan tinker --execute="
use Beike\\Admin\\Repositories\\AdminUserRepo;
AdminUserRepo::createAdminUser([
    'name' => 'admin',
    'email' => 'admin@beikeshop.com',
    'password' => '***',
    'locale' => 'zh_cn',
    'active' => true
]);
echo 'Admin created successfully';
"
```

### Step 9: Mark Installation Complete

```bash
docker exec ${PROJECT_NAME}-nginx bash -c "touch /var/www/html/storage/installed"
```

## Configuration Parameters

### Installation Parameters

| Parameter | Default | Description |
|-----------|---------|-------------|
| `PROJECT_NAME` | `beikeshop` | Project name, used for container naming |
| `DOMAIN` | `localhost` | Website domain |
| `DB_NAME` | `beikeshop` | Database name |
| `DB_USER` | `beikeshop` | Database user |
| `DB_PASSWORD` | Auto-generated | Database password |
| `ADMIN_EMAIL` | `***` | Admin email |
| `ADMIN_PASSWORD` | `***` | Admin password |

### Docker-Specific Parameters

| Parameter | Default | Description |
|-----------|---------|-------------|
| `HTTP_PORT` | `80` | HTTP port mapping on host |
| `HTTPS_PORT` | `443` | HTTPS port mapping on host |
| `DB_PORT` | `3306` | MySQL port mapping on host |
| `DB_ROOT_PASSWORD` | `***` | MySQL root password |

## Upgrade Process

### Upgrade Steps

```bash
# 1. Backup database
docker exec ${PROJECT_NAME}-mysql mysqldump -u root -p"${DB_ROOT_PASSWORD}" ${DB_NAME} > backup_$(date +%Y%m%d).sql

# 2. Backup files
tar -czf backup_www_$(date +%Y%m%d).tar.gz www/

# 3. Pull latest version
cd www
git pull origin master   # or download new release zip

# 4. Update dependencies
docker exec ${PROJECT_NAME}-nginx composer install --no-dev --optimize-autoloader

# 5. Run migrations
docker exec ${PROJECT_NAME}-nginx php artisan migrate --force

# 6. Clear cache
docker exec ${PROJECT_NAME}-nginx php artisan config:clear
docker exec ${PROJECT_NAME}-nginx php artisan cache:clear
docker exec ${PROJECT_NAME}-nginx php artisan view:clear

# 7. Verify
curl -s -o /dev/null -w "%{http_code}" http://localhost
```

### Version Check

```bash
# Check current version
docker exec ${PROJECT_NAME}-nginx php artisan --version
cat www/beike/Config/app.php | grep version
```

## API Reference

BeikeShop provides a RESTful API for headless commerce integration.

### API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/products` | List products |
| GET | `/api/products/{id}` | Product detail |
| GET | `/api/categories` | List categories |
| POST | `/api/cart/add` | Add to cart |
| GET | `/api/cart` | Get cart contents |
| POST | `/api/checkout` | Create order |
| GET | `/api/orders` | List orders (authenticated) |

### API Authentication

API uses Laravel Sanctum token authentication:

```bash
# Get token
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"***","password":"***"}'

# Use token
curl http://localhost/api/orders \
  -H "Authorization: Bearer {token}"
```

### API Documentation

- [Official API Docs](https://docs.beikeshop.com/dev/api.html)
- Swagger/OpenAPI spec available at `/api/documentation` (if enabled)

## Troubleshooting

### 1. Permission Issues

```bash
# Fix storage directory permissions
chmod -R 775 storage bootstrap/cache

# Docker environment
chown -R www-data:www-data storage bootstrap/cache

# BT Panel environment
chown -R www:www storage bootstrap/cache
```

### 2. Database Connection Failed

```bash
# Check .env configuration
cat .env | grep DB_

# Test database connection
php artisan tinker --execute="DB::connection()->getPdo();"
```

### 3. APP_URL Warning

```bash
# Update APP_URL in .env
sed -i 's|APP_URL=.*|APP_URL=http://your-domain|' .env
php artisan config:clear
```

## Security Recommendations

1. ✅ Change default admin password immediately in production
2. ✅ Configure HTTPS (use Let's Encrypt)
3. ✅ Regular database backups
4. ✅ Restrict admin panel access by IP
5. ✅ Enable firewall rules
6. ✅ Keep system updated

## Documentation

- [BeikeShop Official Docs](https://www.beikeshop.com/docs)
- [Installation Guide](./docs/INSTALL.md)
- [Publishing Guide](./docs/PUBLISH.md)

## License

BeikeShop is licensed under OSL 3.0. Please retain the footer copyright or purchase a license.
