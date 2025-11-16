FROM composer:2.6.5 AS composer

# Dockerfile (php)
FROM php:8.2-fpm-bookworm

# Paquetes de sistema para extensiones PHP
RUN apt-get update  \
    && apt-get install -y --no-install-recommends \
        git                                       \
        nano                                      \
        zip                                       \
        unzip                                     \
        zlib1g-dev                                \
        libpng-dev                                \
        libjpeg62-turbo-dev                       \
        libfreetype6-dev                          \
        libicu-dev                                \
        libzip-dev                                \
        libxml2-dev                               \
        libmagickwand-dev                         \
        openssh-client                            \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install imagick

# Extensiones PHP (separado del COPY para evitar el error)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) pdo_mysql zip intl gd soap sockets

# Composer dentro de la imagen (paso separado; antes lo tenías dentro del RUN y fallaba)
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html