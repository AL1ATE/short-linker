#!/bin/sh

mkdir -p /app/runtime /app/web/assets
chmod -R 777 /app/runtime /app/web/assets

exec php-fpm