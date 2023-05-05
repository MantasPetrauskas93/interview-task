FROM php:8.1.17-apache AS base

RUN docker-php-ext-install pdo_mysql && \
    a2enmod allowmethods rewrite

COPY docker/vhost.conf /etc/apache2/sites-enabled/000-default.conf
COPY docker/ports.conf /etc/apache2/ports.conf
