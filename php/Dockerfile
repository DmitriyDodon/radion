FROM php:8.0-fpm

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

WORKDIR /var/www/symfony_docker

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN git config --global user.email "dumassick@gmail.com" \ \
    && git config --global user.name "Dmitriy Dodon"
    
RUN curl -o apk-tools-static-2.6.8-r1.apk http://dl-cdn.alpinelinux.org/alpine/v3.5/main/x86_64/apk-tools-static-2.6.9-r0.apk \
&&  tar -zxvf apk-tools-static-2.6.8-r1.apk \
&&  cd sbin \
&&  ./apk.static -X http://dl-cdn.alpinelinux.org/alpine/latest-stable/main -U --allow-untrusted --initdb add apk-tools-static \
&&  apk.static update \
&&  ./apk.static -X http://dl-cdn.alpinelinux.org/alpine/latest-stable/main -U --allow-untrusted add apk-tools \
&&  apk update
    
RUN apk add yarn
