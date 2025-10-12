FROM php:8.2-fpm-bookworm
RUN apt-get update && apt-get install -y         git unzip libicu-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev      && docker-php-ext-configure gd --with-jpeg --with-freetype      && docker-php-ext-install -j$(nproc) intl zip pdo_mysql gd opcache      && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www/html