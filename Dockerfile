FROM php:7.1-apache
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo_mysql

# install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

COPY docker/config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY docker/config/symfony-site.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/symfony/
VOLUME /var/www/symfony/

COPY . .