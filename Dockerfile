# Step 1: Build Frontend Assets (Vite/Tailwind)
FROM node:20 AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# Step 2: Build PHP/Apache Environment
FROM php:8.2-apache

# Install required system packages (Database packages removed)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install zip

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Update Apache Document Root to point to Laravel's public/ directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy compiled frontend assets from the Node stage
COPY --from=frontend /app/public/build ./public/build

# Install Composer and project dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Set proper directory permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Setup start script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

# Run the start script
CMD ["start.sh"]