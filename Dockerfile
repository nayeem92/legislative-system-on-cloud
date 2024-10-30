# Use the official PHP image with Apache
FROM php:7.4-apache

# Enable necessary PHP extensions
RUN docker-php-ext-install mysqli

# Copy your application files into the container
COPY ./public /var/www/html/
COPY ./src /var/www/src/

# Set the working directory
WORKDIR /var/www/html

# Change ownership of the files to the Apache user
RUN chown -R www-data:www-data /var/www/html /var/www/src

# Expose port 80 to the outside world
EXPOSE 80

# Set the server name to suppress the "could not reliably determine the server's fully qualified domain name" warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable mod_rewrite (if necessary for your application)
RUN a2enmod rewrite
