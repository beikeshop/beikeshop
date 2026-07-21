FROM php:8.2-fpm

# Install system dependencies + Nginx + Node.js 22
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    ca-certificates \
    gnupg \
    libcurl4-openssl-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    bash \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && if ! command -v npm >/dev/null 2>&1; then apt-get install -y npm; fi \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        curl \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        simplexml \
        zip \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Nginx site config
COPY nginx-site.conf /etc/nginx/conf.d/default.conf

# Remove default nginx page if present
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/sites-available/default

# Create log directories
RUN mkdir -p /var/log/nginx /var/log/php /run/php

# Startup script: fix writable directories, run PHP-FPM, then Nginx in foreground
RUN printf '#!/bin/sh\nmkdir -p storage bootstrap/cache public\nchown -R www-data:www-data storage bootstrap/cache public\nchmod -R ug+rwX storage bootstrap/cache public\nphp-fpm -D\nexec nginx -g "daemon off;"\n' > /start.sh \
    && chmod +x /start.sh

WORKDIR /var/www/html

EXPOSE 80 443

CMD ["/start.sh"]
