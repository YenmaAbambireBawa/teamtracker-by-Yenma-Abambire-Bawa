FROM php:8.2-cli

# Install system dependencies and PHP extensions required by Laravel
RUN apt-get update && apt-get install -y \
    unzip \
    libsqlite3-dev \
    libxml2-dev \
    && docker-php-ext-install \
        pdo \
        pdo_sqlite \
        mbstring \
        xml \
        ctype \
        fileinfo \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy dependency manifests first for better layer caching
COPY composer.json composer.lock* ./

# Install PHP dependencies (no dev, optimised autoloader)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy the rest of the application
COPY . .

# Run post-install scripts now that the full app is present
RUN composer run-script post-autoload-dump --no-interaction || true

# Create required runtime directories and set permissions
RUN mkdir -p \
        database \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache/data \
        storage/logs \
        bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache database

# Railway injects APP_KEY, APP_ENV, DB_* etc. as environment variables at
# runtime — no .env file is generated here.
# The start command creates the SQLite database file, runs migrations, then
# starts the built-in PHP server on the PORT provided by Railway.
CMD touch /app/database/tracker.db \
    && php artisan migrate --force \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
