FROM php:7.1-alpine
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

RUN apk update && apk add --no-cache \
    openssl \
    git \
    unzip

RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS && \
    pecl install xdebug && \
    apk del .build-deps && \
    rm -rf /var/cache/apk/*

# Install Composer
RUN curl --silent --show-error -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-enable xdebug

COPY docker/config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/symfony/
VOLUME /var/www/symfony/

COPY . .

EXPOSE 8000
ENTRYPOINT ["php", "bin/console", "server:run", "0.0.0.0:8000"]