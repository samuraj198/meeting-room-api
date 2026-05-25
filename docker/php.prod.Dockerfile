FROM php:8.4-fpm

# Установка системных зависимостей и Nginx
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    nginx \
    && docker-php-ext-install pdo_mysql pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка расширений Redis и PCOV
RUN pecl install redis && docker-php-ext-enable redis \
    && pecl install pcov && docker-php-ext-enable pcov \
    && echo "pcov.directory=/var/www/html" >> /usr/local/etc/php/conf.d/docker-php-ext-pcov.ini

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Копируем исходный код
COPY ./src /var/www/html

# Настраиваем Nginx для продакшена
COPY ./docker/nginx/nginx-prod.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Разрешаем PHP-FPM работать от root, чтобы избежать проблем с правами на Render
RUN sed -i 's/user = www-data/user = root/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/group = www-data/group = root/g' /usr/local/etc/php-fpm.d/www.conf

EXPOSE 80

# При старте сначала ставим зависимости в чистом окружении, сбрасываем кэш и запускаем
CMD ["sh", "-c", "composer install --optimize-autoloader --no-dev --no-scripts && php artisan config:clear && php artisan cache:clear && php-fpm -D && nginx -g 'daemon off;'"]
