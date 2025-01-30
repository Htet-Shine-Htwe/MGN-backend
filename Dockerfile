# Use PHP 8.1
FROM php:8.2-fpm as template

ARG user=radian
ARG uid=1000

# Install common PHP extension dependencies
RUN apt-get update && apt-get install -y \
    git \
    bash \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    libpq-dev \
    unzip \
    libonig-dev \
    libmagickwand-dev --no-install-recommends \
    wget \
    curl \
    supervisor && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) mbstring exif pcntl bcmath zip gd opcache sockets && \
    pecl install imagick && \
    docker-php-ext-enable imagick && \
    docker-php-ext-enable sockets

# Set the working directory
WORKDIR /var/www/mgn

# Copy composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy source code
COPY . .

# Install composer dependencies
RUN composer install --prefer-dist --optimize-autoloader --no-interaction && \
    composer dump-autoload --optimize

# Add user and set permissions
RUN useradd -G www-data,root -u $uid -d /home/$user $user && \
    mkdir -p /home/$user/.composer /usr/local/var/log /var/run && \
    touch /usr/local/var/log/php-fpm.log && \
    chown -R $user:$user /home/$user /var/www/mgn /usr/local/var/log /etc/supervisor /var/log/supervisor && \
    chmod -R 775 /var/www/mgn/storage /var/www/mgn/public && \
    chmod 777 /var/run

USER $user

# Copy custom PHP-FPM configuration
COPY deployment/config/fpm/custom-php-fpm.conf /usr/local/etc/php-fpm.d/

# Copy entrypoint script and ensure it's executable
COPY ./deployment/entrypoint.sh /entrypoint.sh
RUN chmod +x ./deployment/entrypoint.sh

EXPOSE 9001

FROM template as api

ENTRYPOINT ["./deployment/entrypoint.sh"]

# Worker Image
FROM template as worker

# Copy Supervisor configuration
COPY deployment/config/supervisor /etc/supervisor/conf.d
COPY deployment/config/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

CMD ["sh", "-c", "/entrypoint.sh && supervisord -n -c /etc/supervisor/supervisord.conf"]
# CMD ["supervisord"]
