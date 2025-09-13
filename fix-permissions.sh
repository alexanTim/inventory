#!/bin/bash

# Quick fix for Laravel permission issues in Docker
echo "🔧 Fixing Laravel permissions..."

# Fix storage permissions
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage

# Fix bootstrap cache permissions
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/html/bootstrap/cache

# Ensure cache data directory is writable
docker-compose exec app chmod -R 777 /var/www/html/storage/framework/cache/data

# Clear Laravel caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear

echo "✅ Permissions fixed! Try logging in again." 