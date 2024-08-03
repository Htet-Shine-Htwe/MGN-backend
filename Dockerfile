# Use PHP 8.1
FROM php:8.1-fpm

# Install common php extension dependencies
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

# Install composer dependencies
RUN composer install --no-scripts --no-autoloader

# Run composer dump-autoload after copying application files
RUN composer dump-autoload --optimize

# Change ownership and permissions
RUN chown -R www-data:www-data /var/www/mgn && \
    chmod -R 775 /var/www/mgn/storage /var/www/mgn/public

# Create .env file and generate key
RUN cp .env.example .env && \
    php artisan key:generate

COPY ./supervisor /etc/supervisor/conf.d/

EXPOSE 9000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
# Set the default command to run php-fpm
