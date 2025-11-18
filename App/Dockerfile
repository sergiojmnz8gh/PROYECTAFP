FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl libpng-dev && \
    docker-php-ext-install pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

RUN composer require league/plates phpmailer/phpmailer dompdf/dompdf

RUN a2enmod rewrite

COPY vhost.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
