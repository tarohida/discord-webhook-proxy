FROM php:8.0-fpm

MAINTAINER Taro Hida <sk8trou@gmail.com>

RUN apt-get update \
    && apt-get install -y \
        apt-utils \
    && apt-get install -y \
        libpq-dev \
        libzip-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pgsql \
    && docker-php-ext-install -j$(nproc) pdo_pgsql
