FROM php:7.1-fpm

MAINTAINER Giancarlos Salas <giansalex@gmail.com>

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl --silent --show-error -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo_mysql

# install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

ADD docker/config/php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/symfony
VOLUME /var/www/symfony

COPY . .

RUN echo 'alias sf="php bin/console"' >> ~/.bashrc

ENTRYPOINT ["sf", "server:run"]
