FROM php:7-apache

RUN apt-get update; apt-get install libpq-dev git -y
RUN docker-php-ext-install pgsql
RUN a2enmod rewrite

COPY apache2.conf /etc/apache2/apache2.conf
