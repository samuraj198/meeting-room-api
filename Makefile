.PHONY: up down build artisan test test-rooms test-bookings

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

test-rooms:
	docker-compose exec app php artisan test tests/Feature/RoomControllerTest.php

test-bookings:
	docker-compose exec app php artisan test tests/Feature/BookingControllerTest.php
%:
	@: