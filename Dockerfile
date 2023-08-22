FROM php:8.1.6-fpm-alpine

WORKDIR /var/www/app

RUN apk update && apk add \
    build-base \
    git \
    curl \
    zip \
    libzip-dev \
    libpq-dev \
    unzip \
    nano

RUN docker-php-ext-configure mysql
RUN docker-php-ext-install pdo pdo_mysql mysql zip exif pcntl

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

USER root

RUN chmod 777 -R /var/www/app
