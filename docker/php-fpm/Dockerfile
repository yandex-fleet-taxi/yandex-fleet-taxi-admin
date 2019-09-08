FROM php:7-fpm-alpine

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install bcmath

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
    && pecl install xdebug-2.7.2 \
    && docker-php-ext-enable xdebug \
    && apk del pcre-dev ${PHPIZE_DEPS}
