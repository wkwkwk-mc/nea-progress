#!/bin/bash

# Ensure the database file exists and has correct permissions for Apache (www-data)
touch database/database.sqlite
chown -R www-data:www-data storage database bootstrap/cache
chmod -R 775 storage database bootstrap/cache

# Run database migrations to provision session and cache tables
php artisan migrate --force

# Cache configuration, routes, and views for production performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache in the foreground
echo "Starting server..."
apache2-foreground