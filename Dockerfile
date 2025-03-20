# Use the official PHP image with Apache
FROM php:8.1-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache modules
RUN a2enmod proxy proxy_http rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy app files to the container
COPY . /var/www/html

# Set file permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html


# Restart Apache to apply configurations
CMD ["apache2-foreground"]
