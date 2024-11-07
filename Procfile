web: vendor/bin/heroku-php-apache2 public/

worker: php artisan queue:restart && worker: php artisan queue:work redis --queue=normal --sleep=3 --tries=3 --daemon
