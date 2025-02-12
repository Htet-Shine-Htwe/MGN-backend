run-local:
	@php artisan serve --port=8081

run:
	@php artisan serve --port=8081 --host=0.0.0.0

run-octane:
	@php artisan octane:start --server=roadrunner --host=0.0.0.0 --port=8081 --watch

seed:
	@php artisan migrate:fresh --seed

phpstan:
	./vendor/bin/phpstan analyse

pest:
	./vendor/bin/pest

pest-parallel:
	./vendor/bin/pest --parallel

stan-pest:
	./vendor/bin/phpstan analyse && ./vendor/bin/pest --parallel

up:
	@docker-compose up -d

up-build:
	@docker-compose up -d --build

down:
	@docker-compose down

d-restart:
	@docker-compose down
	@docker-compose  up -d

d-restart-build:
	@docker-compose down
	@docker-compose --env-file .env.dev up -d --build

d-app:
	@docker exec -it mgn-app bash

