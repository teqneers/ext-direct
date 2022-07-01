ARG PHP_IMAGE=php:8.1-cli

FROM $PHP_IMAGE
LABEL maintainer="Stefan Gehrig <gehrig@teqneers.de>"

RUN apt-get update \
    && apt-get install -y \
        git \
        wget \
        unzip \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-install -j$(nproc) intl

COPY ./php.ini $PHP_INI_DIR/conf.d/dev.ini

# install composer
COPY --from=composer:2.1.14 /usr/bin/composer /usr/bin/composer

# install xdebug
RUN docker-php-ext-install -j$(nproc) zip \
     && pecl install xdebug \
     && docker-php-ext-enable xdebug
