#!/bin/sh

echo "START: PORT=${PORT}"

# Write .env
cat > /app/.env <<ENVEOF
APP_NAME="Team Activity Tracker"
APP_ENV=production
APP_DEBUG=true
APP_KEY=${APP_KEY}
DB_CONNECTION=sqlite
DB_DATABASE=/app/database/tracker.db
SESSION_DRIVER=file
CACHE_STORE=file
LOG_CHANNEL=stderr
ENVEOF

mkdir -p /app/database /app/storage/framework/{sessions,views,cache/data} /app/storage/logs /app/bootstrap/cache
touch /app/database/tracker.db
chmod -R 777 /app/storage /app/database /app/bootstrap/cache

echo "MIGRATING"
php /app/artisan migrate --force 2>&1

echo "SEEDING"
php /app/artisan db:seed --force 2>&1

echo "NGINX CONFIG"
envsubst '${PORT}' < /app/nginx.conf > /etc/nginx/http.d/default.conf
cat /etc/nginx/http.d/default.conf

echo "STARTING PHP-FPM"
php-fpm -D

echo "STARTING NGINX on port ${PORT}"
exec nginx -g "daemon off;"
