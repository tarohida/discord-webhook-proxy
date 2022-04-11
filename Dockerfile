FROM php:8.0-apache

MAINTAINER Taro Hida <sk8trou@gmail.com>

WORKDIR /var/www
RUN apt-get update \
    && apt-get install -y \
        apt-utils \
    && apt-get install -y \
        libpq-dev \
        libzip-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install -j$(nproc) zip
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock /var/www/
RUN composer install --no-dev
COPY app/ /var/www/app/
COPY public/ /var/www/public/
COPY src/ /var/www/src/
COPY var/ /var/www/var/
ENV docker=true
ENV production=true
COPY ./upload.ini /usr/local/etc/php/
CMD php -S 0.0.0.0:8080 -t public
