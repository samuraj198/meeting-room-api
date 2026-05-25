FROM php:8.4-fpm

# Установка системных зависимостей и Nginx
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    nginx \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql pcntl \
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

# Устанавливаем зависимости Laravel сразу во время сборки образа (так надежнее)
RUN composer install --optimize-autoloader --no-dev --no-scripts --prefer-dist

# Выдаем права пользователю www-data на весь проект, чтобы PHP-FPM мог писать кэш и логи
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Команда запуска: выполняем миграции, очищаем конфигурацию и стартуем от www-data
CMD ["sh", "-c", "php artisan migrate --force && php artisan config:clear && php artisan cache:clear && php-fpm -D && nginx -g 'daemon off;'"]
