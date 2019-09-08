FROM composer:latest

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install bcmath \
    && apk add composer

RUN ssh-keyscan github.com >> ~/.ssh/known_hosts
