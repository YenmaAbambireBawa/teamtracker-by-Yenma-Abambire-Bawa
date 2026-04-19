#!/bin/sh
set -e

# ---------------------------------------------------------------------------
# Ensure the SQLite database file exists and run pending migrations.
# ---------------------------------------------------------------------------
touch /app/database/tracker.db
php artisan migrate --force

# ---------------------------------------------------------------------------
# PHP-FPM: write a pool config that listens on a Unix socket so Nginx can
# reach it without a TCP port.
# ---------------------------------------------------------------------------
cat > /usr/local/etc/php-fpm.d/zz-railway.conf <<'EOF'
[www]
listen = /tmp/php-fpm.sock
listen.mode = 0666
EOF

# ---------------------------------------------------------------------------
# Start PHP-FPM in the background first, then wait for the socket to appear
# before starting Nginx (avoids a race where Nginx starts before FPM is ready).
# ---------------------------------------------------------------------------
php-fpm --nodaemonize &
FPM_PID=$!

echo "Waiting for PHP-FPM socket..."
for i in $(seq 1 30); do
    [ -S /tmp/php-fpm.sock ] && break
    sleep 1
done

if [ ! -S /tmp/php-fpm.sock ]; then
    echo "ERROR: PHP-FPM socket did not appear after 30 seconds" >&2
    exit 1
fi
echo "PHP-FPM socket ready."

# ---------------------------------------------------------------------------
# Nginx: substitute $PORT into the config at runtime, validate, then start.
# envsubst only expands the variables listed — other nginx variables like
# $uri are left untouched.
# ---------------------------------------------------------------------------
export PORT="${PORT:-8000}"
envsubst '${PORT}' < /app/nginx.conf > /tmp/nginx.conf

nginx -c /tmp/nginx.conf -t   # validate — exits non-zero on bad config
nginx -c /tmp/nginx.conf      # start in foreground (daemon off by default in Alpine)

# If Nginx exits, bring down PHP-FPM too so Railway restarts the container.
kill "$FPM_PID"
