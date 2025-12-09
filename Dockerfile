# ============================================
# PRODUCTION-READY DOCKERFILE
# All-in-One: Nginx + PHP-FPM + Supervisor
# ============================================

FROM php:8.2-fpm-alpine

# Install system dependencies dan PHP extensions
RUN apk add --no-cache \
    bash \
    curl \
    nginx \
    supervisor \
    libpng-dev \
    libzip-dev \
    mysql-client \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring zip gd exif pcntl bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy hanya composer files dulu untuk caching layer
COPY composer.json ./
# Copy composer.lock jika ada (optional untuk backward compatibility)
COPY composer.lock* ./

# Install Composer dependencies (production mode)
# Jalankan SEBELUM copy semua files untuk optimize Docker cache
# Gunakan --ignore-platform-reqs jika ada masalah dengan platform requirements
RUN if [ -f composer.lock ]; then \
        composer install --no-dev --no-scripts --no-autoloader --no-interaction --ignore-platform-reqs; \
    else \
        composer install --no-dev --no-scripts --no-autoloader --no-interaction --ignore-platform-reqs; \
    fi

# Copy application files (setelah composer install)
COPY . /var/www

# Generate autoload files (setelah semua files di-copy)
RUN composer dump-autoload --optimize --no-dev

# Copy Nginx configuration
# Alpine Linux menggunakan /etc/nginx/http.d/ bukan /etc/nginx/conf.d/
RUN rm -rf /etc/nginx/http.d/default.conf
COPY docker/nginx/nginx.conf /etc/nginx/http.d/default.conf

# Copy PHP configuration untuk upload file besar
COPY docker/php/php.ini /usr/local/etc/php/conf.d/upload.ini

# Copy Supervisor configuration
COPY docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create storage directories jika belum ada
RUN mkdir -p /var/www/storage/app/public/ktp \
    && mkdir -p /var/www/storage/app/public/paspor \
    && mkdir -p /var/www/storage/app/public/photos \
    && mkdir -p /var/www/storage/app/public/signatures \
    && mkdir -p /var/www/storage/framework/cache \
    && mkdir -p /var/www/storage/framework/sessions \
    && mkdir -p /var/www/storage/framework/views \
    && mkdir -p /var/www/storage/logs \
    && mkdir -p /var/log/supervisor \
    && mkdir -p /var/log/nginx

# Set correct permissions (hanya untuk folder spesifik, bukan semua /var/www)
RUN chown -R www-data:www-data /var/www/storage \
    && chown -R www-data:www-data /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Create symbolic link for storage (relative path)
RUN cd /var/www/public && ln -sf ../storage/app/public storage

# Expose Nginx port (bukan PHP-FPM)
EXPOSE 80

# Start Supervisor (akan manage Nginx + PHP-FPM)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]