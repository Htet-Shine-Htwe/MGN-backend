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
