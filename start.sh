#!/bin/sh

# replace env vars in nginx config
envsubst '$PORT' < /app/nginx.conf > /etc/nginx/nginx.conf

# start php-fpm in background
php-fpm -D

# start nginx in foreground
nginx -g "daemon off;"
