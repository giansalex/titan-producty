FROM php:7.1-apache
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo_mysql

# install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

ADD docker/config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/html/
VOLUME /var/www/html/

COPY . .

ENTRYPOINT ["php", "bin/console", "server:start", "0.0.0.0:8000"]
