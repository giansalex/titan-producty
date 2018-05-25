FROM php:7.1.17-cli
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo pdo_mysql

# install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

COPY docker/config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/symfony/
VOLUME /var/www/symfony/

COPY . .

EXPOSE 8000
ENTRYPOINT ["php", "bin/console", "server:run", "0.0.0.0:8000"]