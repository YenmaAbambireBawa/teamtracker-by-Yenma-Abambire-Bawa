# ---------------------------------------------------------------------------
# Stage 1 – PHP dependencies
# ---------------------------------------------------------------------------
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# ---------------------------------------------------------------------------
# Stage 2 – Runtime image
# ---------------------------------------------------------------------------
FROM php:8.4-fpm-alpine

# Install Nginx, envsubst (gettext), and required PHP extensions.
RUN apk add --no-cache \
        nginx \
        gettext \
        sqlite \
        sqlite-dev \
        libxml2-dev \
        oniguruma-dev \
    && docker-php-ext-install \
        pdo \
        pdo_sqlite \
        mbstring \
        xml \
        ctype \
        fileinfo \
        opcache

WORKDIR /app

# Copy vendor directory from the build stage.
COPY --from=vendor /app/vendor ./vendor

# Copy the rest of the application source.
COPY . .

# Run post-autoload-dump scripts now that the full app is present.
RUN php artisan package:discover --ansi || true

# Create required runtime directories and set permissions.
RUN mkdir -p \
        database \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache/data \
        storage/logs \
        bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache database

# Copy Nginx config and startup script into well-known locations.
COPY nginx.conf /app/nginx.conf
COPY start.sh   /start.sh
RUN chmod +x /start.sh

EXPOSE 8000

# Railway injects APP_KEY, APP_ENV, DB_* etc. as environment variables at
# runtime. start.sh creates the DB, runs migrations, then starts PHP-FPM
# (background) and Nginx (foreground).
CMD ["/start.sh"]
