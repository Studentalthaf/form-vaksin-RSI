FROM php:8.2-fpm-alpine

# Install dependencies minimal (Alpine lebih kecil dari Debian)
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    mysql-client \
    oniguruma-dev \
    && docker-php-ext-install pdo_mysql mbstring zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . /var/www

# Install dependencies tanpa dev packages dan tanpa scripts (cepat!)
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Expose PHP-FPM port
EXPOSE 9000

CMD ["php-fpm"]