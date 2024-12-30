#!/bin/sh

# Replace the listen directive with the PORT environment variable
# sed -i "s/listen = 0.0.0.0:9001/listen = 0.0.0.0:${PORT}/" /usr/local/etc/php-fpm.d/custom-php-fpm.conf

# Start PHP-FPM
exec php-fpm -y /usr/local/etc/php-fpm.d/custom-php-fpm.conf
