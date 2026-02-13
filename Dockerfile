FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

WORKDIR /var/www/html

RUN sed -i 's/Options -Indexes/Options +Indexes/' /etc/apache2/conf-available/docker-php.conf || echo "Options +Indexes" >> /etc/apache2/apache2.conf

RUN chown -R www-data:www-data /var/www/html