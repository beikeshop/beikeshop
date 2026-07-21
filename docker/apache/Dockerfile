FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libcurl4-openssl-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    zip \
    unzip \
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
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Apache virtual host config
COPY beikeshop.conf /etc/apache2/sites-available/beikeshop.conf

# Disable default site, enable BeikeShop site
RUN a2dissite 000-default && a2ensite beikeshop

# Create log directory
RUN mkdir -p /var/log/php

WORKDIR /var/www/html

EXPOSE 80 443

CMD ["apache2-foreground"]
