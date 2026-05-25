FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    nginx \
    && docker-php-ext-install pdo_mysql pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install pcov && docker-php-ext-enable pcov
RUN echo "pcov.directory=/var/www/html" >> /usr/local/etc/php/conf.d/docker-php-ext-pcov.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./src /var/www/html

RUN composer install --optimize-autoloader --no-dev --no-scripts

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY ./docker/nginx/nginx.prod.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

EXPOSE 80

CMD ["sh", "-c", "composer dump-autoload --optimize && php artisan config:clear && php artisan cache:clear && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && php-fpm -D && nginx -g 'daemon off;'"]