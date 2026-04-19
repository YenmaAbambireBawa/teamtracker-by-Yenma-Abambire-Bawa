#!/bin/sh
set -e

# ---------------------------------------------------------------------------
# Ensure the SQLite database file exists and run pending migrations.
# ---------------------------------------------------------------------------
touch /app/database/tracker.db
php artisan migrate --force

# ---------------------------------------------------------------------------
# PHP-FPM: configure it to listen on a Unix socket so Nginx can reach it
# without needing a TCP port.
# ---------------------------------------------------------------------------

# Write a minimal pool override that switches the listen address to a socket.
cat > /usr/local/etc/php-fpm.d/zz-railway.conf <<'EOF'
[www]
listen = /tmp/php-fpm.sock
listen.mode = 0666
EOF

# ---------------------------------------------------------------------------
# Nginx: substitute $PORT (provided by Railway) into the config at runtime,
# then validate and start Nginx in the background.
# ---------------------------------------------------------------------------
export PORT="${PORT:-8000}"

# envsubst replaces ${PORT:-8000} — we only want to expand PORT, not every
# shell variable that might appear in the config.
envsubst '${PORT}' < /app/nginx.conf > /tmp/nginx.conf

nginx -c /tmp/nginx.conf -t          # validate config
nginx -c /tmp/nginx.conf &           # start in background

# ---------------------------------------------------------------------------
# PHP-FPM: start in the foreground so the container stays alive and Docker /
# Railway can observe its exit code.
# ---------------------------------------------------------------------------
exec php-fpm --nodaemonize
