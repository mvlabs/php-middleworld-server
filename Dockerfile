FROM php:7-apache

RUN apt-get update; apt-get install libpq-dev -y
RUN docker-php-ext-install pgsql

