#!/bin/sh

# inject railway port into nginx config
envsubst '$PORT' < /app/nginx.conf > /etc/nginx/nginx.conf

# start php-fpm
php-fpm -D

# start nginx (this keeps container alive)
nginx -g "daemon off;"
