# Meeting Room API

API для бронирования переговорных комнат. Разработано на Laravel 13 с использованием Docker, Redis, Sanctum и Swagger.

## 📋 Требования

- Docker & Docker Compose
- Make (опционально, для удобства)

## 🚀 Быстрый старт

### Клонировать репозиторий
git clone https://github.com/samuraj198/meeting-room-api.git <br>
cd meeting-room-api

### Скопировать .env
cp .env.example .env

### Запустить контейнеры
docker-compose up -d

### Установить зависимости
docker-compose exec app composer install

### Выполнить миграции и сиды
docker-compose exec app php artisan migrate --seed

### Сгенерировать ключ приложения
docker-compose exec app php artisan key:generate
