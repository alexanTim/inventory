#!/bin/sh

set -e

echo "Starting Gentle Walker application..."

# Change to the application directory
cd /var/www/html

# Environment variables are provided via env_file in docker-compose
echo "Using environment variables from docker-compose env_file..."

# Avoid Composer/Tinker safe.directory errors when running inside container
git config --global --add safe.directory /var/www/html || true

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate --no-interaction
fi

# Wait for database to be ready
echo "Waiting for database connection..."
until php artisan db:show --database=mysql 2>/dev/null; do
    echo "Database not ready, waiting 2 seconds..."
    sleep 2
done

echo "Database connection established!"

# Publish Livewire assets (non-interactive)
echo "Publishing Livewire assets..."
php artisan livewire:publish --assets || echo "Livewire asset publishing failed, continuing..."

# Skip Flux asset publishing during container boot to avoid interactive prompts
# Assets are built via npm during the image build step.

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force --no-interaction || echo "Migration failed or already up to date, continuing..."

# Clear cache first to avoid permission issues
echo "Clearing cache..."
php artisan cache:clear || echo "Cache clear failed, continuing..."
php artisan config:clear || echo "Config clear failed, continuing..."
php artisan route:clear || echo "Route clear failed, continuing..."
php artisan view:clear || echo "View clear failed, continuing..."

# Cache optimization
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if it doesn't exist
if [ ! -L "public/storage" ]; then
    echo "Creating storage symlink..."
    php artisan storage:link
fi

# Fix permissions - handle volume mounts properly
echo "Fixing file permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure cache directories exist and have proper permissions
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Set specific permissions for cache directories
chown -R www-data:www-data /var/www/html/storage/framework/cache
chown -R www-data:www-data /var/www/html/storage/framework/sessions
chown -R www-data:www-data /var/www/html/storage/framework/views
chown -R www-data:www-data /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/bootstrap/cache

chmod -R 775 /var/www/html/storage/framework/cache
chmod -R 775 /var/www/html/storage/framework/sessions
chmod -R 775 /var/www/html/storage/framework/views
chmod -R 775 /var/www/html/storage/logs
chmod -R 775 /var/www/html/bootstrap/cache

# Ensure the cache data directory is writable
chmod -R 777 /var/www/html/storage/framework/cache/data

# Ensure backup directories exist and are writable (Spatie backup)
mkdir -p /var/www/html/storage/app/private
mkdir -p /var/www/html/storage/app/Laravel
chown -R www-data:www-data /var/www/html/storage/app
chmod -R 775 /var/www/html/storage/app

echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf