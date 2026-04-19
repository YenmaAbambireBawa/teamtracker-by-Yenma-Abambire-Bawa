#!/bin/sh
set -e

# Log everything to stderr so Railway captures it
exec 2>&1

echo "=== Starting up, PORT=$PORT ==="

mkdir -p /app/database /app/storage/framework/sessions /app/storage/framework/views \
         /app/storage/framework/cache/data /app/storage/logs /app/bootstrap/cache
touch /app/database/tracker.db
chmod -R 777 /app/storage /app/database /app/bootstrap/cache

printf "APP_NAME=\"Team Activity Tracker\"\nAPP_ENV=production\nAPP_DEBUG=false\nAPP_KEY=%s\nDB_CONNECTION=sqlite\nDB_DATABASE=/app/database/tracker.db\nSESSION_DRIVER=file\nSESSION_LIFETIME=480\nCACHE_STORE=file\nLOG_LEVEL=error\n" "$APP_KEY" > /app/.env

echo "=== Running migrations ==="
php artisan migrate --force

echo "=== Running seeds ==="
php artisan db:seed --force

echo "=== Substituting PORT=$PORT into nginx config ==="
envsubst '${PORT}' < /app/nginx.conf > /etc/nginx/http.d/default.conf

echo "=== Starting PHP-FPM ==="
php-fpm -D

echo "=== Starting nginx ==="
exec nginx -g "daemon off;"
