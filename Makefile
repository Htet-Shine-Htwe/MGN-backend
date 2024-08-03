run-local:
	@php artisan serve --port=8080

run:
	@php artisan serve --port=8080 --host=0.0.0.0

seed:
    @php artisan migrate:fresh --seed
