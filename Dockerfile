FROM php:7-apache
RUN cat /etc/apache2/apache2.conf|sed 's/DocumentRoot.*/DocumentRoot \/var\/www\/html\/public\//' > /etc/apache2/apache2.conf.zf2 ; mv /etc/apache2/apache2.conf.zf2 /etc/apache2/apache2.conf

RUN apt-get update; apt-get install libpq-dev -y
RUN docker-php-ext-install pgsql
RUN a2enmod rewrite

RUN apt-get install git -y