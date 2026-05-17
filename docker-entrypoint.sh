#!/bin/sh
set -e

# Install composer dependencies if vendor folder is missing or composer.json changed
if [ ! -d "vendor" ]; then
    composer install --no-interaction --optimize-autoloader
fi

# Ensure permissions on the var folder for Symfony cache/logs
chown -R www-data:www-data /var/www/html/var || true
chmod -R 777 /var/www/html/var || true

# Wait for the database to be ready and run migrations
echo "Waiting for database connection..."
until php -r "new PDO('pgsql:host=db;port=5432;dbname=app_db', 'app_db', 'secret');" > /dev/null 2>&1; do
    echo "Waiting for database..."
    sleep 2
done
echo "Running database migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

exec "$@"