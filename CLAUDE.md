# CLAUDE.md

This file provides guidance to Claude Code when working with BeikeShop.

## Project Overview

BeikeShop - Open-source cross-border e-commerce system built on Laravel, supporting multi-language and multi-currency.

**Tech Stack:**
- PHP 8.0+
- Laravel Framework
- MySQL 5.7+ / 8.0
- Nginx / Apache

## Common Commands

### Installation

```bash
# CLI installation (recommended)
php artisan beikeshop:install --force

# One-click script installation
./install.sh

# Docker deployment
docker-compose up -d
docker-compose exec php php artisan beikeshop:install --force
```

### Development

```bash
# Start development server
php artisan serve

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Database migration
php artisan migrate
php artisan migrate:fresh --seed

# Generate app key
php artisan key:generate
```

### Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=TestName
```

## Project Structure

```
beikeshop/
├── app/                    # Application core code
│   ├── Console/           # Console commands
│   ├── Http/              # HTTP controllers, middleware
│   └── Models/            # Eloquent models
├── beike/                 # BeikeShop core modules
│   ├── Admin/             # Admin panel module
│   ├── Shop/              # Frontend shop module
│   └── Installer/         # Installation module
├── config/                # Configuration files
├── database/              # Migrations and seeders
├── resources/             # Views, assets, lang files
├── routes/                # Route definitions
├── storage/               # Logs, cache, uploads
└── public/                # Public assets, index.php
```

## Key Files

- `AGENTS.md` - AI Agent installation instructions
- `install.sh` - One-click installation script
- `docker-compose.yml` - Docker orchestration
- `.env.example` - Environment variable template

## Important Notes

1. **Installation marker**: `storage/installed` file indicates installation status
2. **Admin panel**: Accessible at `/admin` route
3. **Multi-language**: Language files in `resources/lang/`
4. **Cache**: Clear cache after config changes with `php artisan config:clear`

## Security

- Never commit `.env` file
- Change default admin password in production
- Use HTTPS in production environment
- Regular database backups
