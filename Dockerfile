FROM php:8.0-fpm-alpine as base

RUN apk update \
    && apk add --no-cache freetype libpng libjpeg-turbo libzip libsodium gmp libmcrypt git openssh \
    # Install build packages
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS freetype-dev libpng-dev libjpeg-turbo-dev zlib-dev libzip-dev \
       libxml2-dev libsodium-dev gmp-dev libmcrypt-dev \
    && apk add postgresql-dev \
    && NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j${NPROC} pdo pdo_pgsql pgsql \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && apk del .build-deps && rm -rf /var/cache/apk/*

FROM base AS dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    bzip2-dev freetype-dev gettext-dev icu-dev imagemagick-dev libintl libjpeg-turbo-dev \
    libpng-dev libxslt-dev libzip-dev \
    && mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY ./docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN yes | pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN apk del .build-deps \
    && rm -rf /var/cache/apk/*

