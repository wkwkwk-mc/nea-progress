#!/bin/bash

# 1. Create the missing SQLite database file
touch database/database.sqlite

# 2. Run migrations to create the required default tables (like 'sessions' and 'cache')
php artisan migrate --force

# 3. Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Start Apache in the foreground
echo "Starting server..."
apache2-foreground