run-local:
	@php artisan serve --port=8081

run:
	@php artisan serve --port=8081 --host=0.0.0.0

seed:
	@php artisan migrate:fresh --seed

phpstan:
	./vendor/bin/phpstan analyse
