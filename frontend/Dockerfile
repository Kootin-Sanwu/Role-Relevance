# Use an official PHP + Apache image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install PHP extensions needed for DB connection
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy frontend code into the container
COPY frontend/ /var/www/html/

# Enable Apache Rewrite Module (optional, only if you're using pretty URLs)
RUN a2enmod rewrite

# Expose HTTP port
EXPOSE 80
