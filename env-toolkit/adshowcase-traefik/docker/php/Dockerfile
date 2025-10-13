FROM php:8.2-fpm-bookworm

# Paquetes necesarios para extensiones y Composer
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        git unzip ca-certificates pkg-config \
        libzip-dev libicu-dev \
        libpng-dev libjpeg-dev libfreetype6-dev \
        libxml2-dev \
    ; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" pdo_mysql zip intl gd soap sockets; \
    rm -rf /var/lib/apt/lists/*

# Composer dentro de la imagen
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# (Opcional) “hornear” Yii2 para sincronizar luego desde la imagen
ARG YII_VERSION="^2.0"
RUN composer create-project yiisoft/yii2-app-basic /opt/yii2-template --prefer-dist --no-interaction --no-progress

WORKDIR /var/www/html