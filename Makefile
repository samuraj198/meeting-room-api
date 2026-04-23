.PHONY: up down build artisan test

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build --no-cache

artisan:
	docker-compose exec app php artisan $(filter-out $@,$(MAKECMDGOALS))

test:
	docker-compose exec app php artisan test --testsuite=Feature

%:
	@: