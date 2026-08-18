#!/bin/bash

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache in the foreground
echo "Starting server..."
apache2-foreground