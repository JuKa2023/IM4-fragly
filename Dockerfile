# Build stage for frontend
FROM oven/bun:1.1.14 AS build
WORKDIR /app

# Copy package files and install dependencies
COPY app/package.json app/bun.lock ./
RUN bun install --frozen-lockfile

# Copy source and build
COPY app/ ./
RUN bun run build

# Production stage with PHP Apache
FROM php:8.3-apache

# Install PHP extensions and enable Apache modules in one layer
RUN docker-php-ext-install mysqli pdo pdo_mysql && \
    a2enmod rewrite headers

# Copy files
COPY api/ /var/www/html/api/
COPY --from=build /app/dist /var/www/html/

# Create .htaccess and set permissions in one layer
RUN { \
    echo "# Security Headers"; \
    echo "Header always set X-Content-Type-Options nosniff"; \
    echo "Header always set X-Frame-Options DENY"; \
    echo "Header always set X-XSS-Protection \"1; mode=block\""; \
    echo ""; \
    echo "# SPA Routing"; \
    echo "RewriteEngine On"; \
    echo "RewriteCond %{REQUEST_URI} !^/api"; \
    echo "RewriteCond %{REQUEST_FILENAME} !-f"; \
    echo "RewriteCond %{REQUEST_FILENAME} !-d"; \
    echo "RewriteRule . /index.html [L]"; \
    } > /var/www/html/.htaccess && \
    chown -R www-data:www-data /var/www/html

EXPOSE 80