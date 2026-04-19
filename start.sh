#!/bin/sh
set -e

echo "START: PORT=${PORT}"
echo "APP_KEY is set: $([ -n "$APP_KEY" ] && echo YES || echo NO)"

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

echo "ENV written"

mkdir -p /app/database /app/storage/framework/sessions /app/storage/framework/views /app/storage/framework/cache/data /app/storage/logs /app/bootstrap/cache
touch /app/database/tracker.db
chmod -R 777 /app/storage /app/database /app/bootstrap/cache

echo "MIGRATING"
php /app/artisan migrate --force 2>&1 || echo "MIGRATION FAILED (continuing)"

echo "SEEDING"
php /app/artisan db:seed --force 2>&1 || echo "SEED FAILED (continuing)"

echo "NGINX CONFIG"
# Remove default nginx config that conflicts
rm -f /etc/nginx/http.d/default.conf

envsubst '${PORT}' < /app/nginx.conf > /etc/nginx/http.d/default.conf
cat /etc/nginx/http.d/default.conf

echo "Testing nginx config"
nginx -t 2>&1

echo "STARTING PHP-FPM"
php-fpm -D

sleep 1

echo "STARTING NGINX on port ${PORT}"
exec nginx -g "daemon off;"
