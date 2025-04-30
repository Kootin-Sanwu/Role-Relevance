# Use an official PHP + Apache image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install PHP extensions you might need (common ones)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy backend code into the container
COPY backend/ /var/www/html/

# Enable Apache Rewrite Module (optional, for pretty URLs if needed)
RUN a2enmod rewrite

# Expose HTTP port
EXPOSE 80

# Start Apache (this is automatically handled by the php:apache base image)
