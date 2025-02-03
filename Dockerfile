# Use PHP 8.1
FROM php:8.2-fpm-alpine AS template

ARG user=radian
ARG uid=1000

# Install common PHP extension dependencies
RUN apk update && apk add \
        git \
        bash \
        freetype-dev \
        libpng-dev \
        jpeg-dev \
        libjpeg-turbo-dev \
        zlib \
        libzip-dev \
        libpq-dev \
        unzip \
        imagemagick \
        oniguruma \
        oniguruma-dev \
        imagemagick-dev \
        libtool \
        wget \
        curl \
        supervisor \
        linux-headers \
        autoconf \
        gcc \
        g++ \
        make && \
        rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
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

# RUN adduser -u $uid -D -h /home/$user -G www-data $user && \
#     chown -R $user:www-data /home/$user /var/www/mgn /usr/local/var/log && \
#     chmod -R 775 /var/www/mgn/storage /var/www/mgn/public

RUN addgroup -S $user && adduser -S $user -G www-data && \
    mkdir -p /etc/supervisor /var/log/supervisor && \
    chown -R $user:www-data /usr/local/var/log /etc/supervisor /var/log/supervisor


# Copy custom PHP-FPM configuration
COPY deployment/config/fpm/custom-php-fpm.conf /usr/local/etc/php-fpm.d/

# Copy entrypoint script and ensure it's executable
COPY ./deployment/entrypoint.sh /entrypoint.sh
RUN chmod +x ./deployment/entrypoint.sh


USER $user


FROM template AS api

EXPOSE 9001

CMD ["sh", "-c", "/entrypoint.sh"]

# Worker Image
FROM php:8.2-fpm-alpine AS worker

RUN apk add --no-cache \
        bash \
        libpq \
        supervisor  # Only if your worker needs supervisor

# Copy the runtime artifacts from the builder stage
WORKDIR /var/www/mgn
COPY --from=template /var/www/mgn /var/www/mgn
COPY --from=template /usr/local/etc/php-fpm.d/ /usr/local/etc/php-fpm.d/
COPY --from=template /entrypoint.sh /entrypoint.sh

# Copy Supervisor configuration for worker
COPY deployment/config/supervisor /etc/supervisor/conf.d
COPY deployment/config/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

CMD ["sh", "-c", "/entrypoint.sh && supervisord -n -c /etc/supervisor/supervisord.conf"]
