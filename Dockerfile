# ============================================
# PRODUCTION-READY DOCKERFILE
# All-in-One: Nginx + PHP-FPM + Supervisor
# ============================================

FROM php:8.2-fpm-alpine

# Install system dependencies
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

# Copy application files
COPY . /var/www

# Install Composer dependencies (production mode)
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction

# Copy Nginx configuration
# Alpine Linux menggunakan /etc/nginx/http.d/ bukan /etc/nginx/conf.d/
RUN rm -rf /etc/nginx/http.d/default.conf
COPY docker/nginx/nginx.conf /etc/nginx/http.d/default.conf

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

# Set correct permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Create symbolic link for storage (relative path)
RUN cd /var/www/public && ln -sf ../storage/app/public storage

# Expose Nginx port (bukan PHP-FPM)
EXPOSE 80

# Start Supervisor (akan manage Nginx + PHP-FPM)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]