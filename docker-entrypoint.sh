#!/bin/sh
chown -R www-data:www-data /var/www/html/var
chmod -R 777 /var/www/html/var
exec "$@"