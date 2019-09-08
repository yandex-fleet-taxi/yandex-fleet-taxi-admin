FROM php:7-cli-alpine

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install bcmath \
    && apk add composer
