#!/bin/sh
echo "=== CONTAINER STARTED ==="
echo "PORT=${PORT}"
echo "APP_KEY set: $([ -n "$APP_KEY" ] && echo YES || echo NO)"

mkdir -p /app/database /app/storage/framework/sessions /app/storage/framework/views /app/storage/framework/cache/data /app/storage/logs /app/bootstrap/cache
touch /app/database/tracker.db
chmod -R 777 /app/storage /app/database /app/bootstrap/cache

cat > /app/.env <<EOF
APP_NAME="Team Activity Tracker"
APP_ENV=production
APP_DEBUG=true
APP_KEY=${APP_KEY}
DB_CONNECTION=sqlite
DB_DATABASE=/app/database/tracker.db
SESSION_DRIVER=file
CACHE_STORE=file
LOG_CHANNEL=stderr
EOF

echo "=== RUNNING MIGRATIONS ==="
php /app/artisan migrate --force 2>&1 || echo "MIGRATION FAILED"

echo "=== WRITING NGINX CONFIG ==="
rm -f /etc/nginx/http.d/default.conf
envsubst '${PORT}' < /app/nginx.conf > /etc/nginx/http.d/default.conf

echo "=== TESTING NGINX ==="
nginx -t 2>&1

echo "=== STARTING PHP-FPM ==="
php-fpm -D

sleep 1

echo "=== STARTING NGINX ==="
exec nginx -g "daemon off;"
