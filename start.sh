#!/bin/sh
set -e

mkdir -p /app/database /app/storage/framework/sessions /app/storage/framework/views \
         /app/storage/framework/cache/data /app/storage/logs /app/bootstrap/cache
touch /app/database/tracker.db
chmod -R 777 /app/storage /app/database /app/bootstrap/cache

printf "APP_NAME=\"Team Activity Tracker\"\nAPP_ENV=production\nAPP_DEBUG=false\nAPP_KEY=%s\nDB_CONNECTION=sqlite\nDB_DATABASE=/app/database/tracker.db\nSESSION_DRIVER=file\nSESSION_LIFETIME=480\nCACHE_STORE=file\nLOG_LEVEL=error\n" "$APP_KEY" > /app/.env

php artisan migrate --force
php artisan db:seed --force

# Substitute $PORT into nginx config and write to where nginx reads it
envsubst '${PORT}' < /app/nginx.conf > /etc/nginx/http.d/default.conf

php-fpm -D
exec nginx -g "daemon off;"
