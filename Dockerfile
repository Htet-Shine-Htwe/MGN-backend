# Use PHP 8.1
FROM php:8.2-fpm

# Install common PHP extension dependencies
RUN apt-get update && apt-get install -y \
    bash \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libonig-dev \
    libmagickwand-dev --no-install-recommends \
    wget \
    curl \
    supervisor

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath zip gd opcache

RUN pecl install imagick && \
    docker-php-ext-enable imagick

# Set the working directory
WORKDIR /var/www/mgn

# Copy composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy source code
COPY . .

RUN rm composer.lock

# Install composer dependencies
RUN composer install --no-scripts --no-autoloader

# Run composer dump-autoload after copying application files
RUN composer dump-autoload --optimize

# Change ownership and permissions
RUN chown -R www-data:www-data /var/www/mgn && \
    chmod -R 775 /var/www/mgn/storage /var/www/mgn/public

# Copy custom PHP-FPM configuration
COPY custom-php-fpm.conf /usr/local/etc/php-fpm.d/

# Copy the entrypoint script
COPY entrypoint.sh /entrypoint.sh

# Ensure the entrypoint script is executable
RUN chmod +x /entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]
