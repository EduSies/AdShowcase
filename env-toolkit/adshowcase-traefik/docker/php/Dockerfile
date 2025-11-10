# Dockerfile (php)
FROM php:8.2-fpm-bookworm

# Paquetes de sistema para extensiones PHP
RUN apt-get update && apt-get install -y \
      git unzip libzip-dev libicu-dev \
      libjpeg62-turbo-dev libpng-dev libfreetype6-dev \
      libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# Extensiones PHP (separado del COPY para evitar el error)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) pdo_mysql zip intl gd soap sockets

# Composer dentro de la imagen (paso separado; antes lo tenías dentro del RUN y fallaba)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html