FROM php:8.4-fpm

RUN docker-php-ext-install pdo_mysql

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html