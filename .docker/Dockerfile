FROM php:8.3-fpm

RUN apt-get update  \
    && apt-get -y install git zip libpq-dev nano bash git curl libonig-dev libpng-dev

RUN apt-get -y install libxml2-dev

RUN docker-php-ext-install mbstring xml

WORKDIR /var/www/project

USER www-data
