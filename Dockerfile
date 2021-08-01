FROM php:8.0.1-fpm

RUN apt-get update && apt-get install -y mariadb-client --no-install-recommends \
   && docker-php-ext-install pdo_mysql && docker-php-ext-install mysqli

RUN apt-get install -y curl nano && \
   curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer self-update --2

RUN apt-get update && apt-get install -y \
   libfreetype6-dev \
   libjpeg62-turbo-dev \
   libpng-dev \
   libzip-dev \
   zip \
   && docker-php-ext-install -j$(nproc) iconv \
   && docker-php-ext-configure gd --with-freetype --with-jpeg \
   && docker-php-ext-install gd \
   && docker-php-ext-install zip \
   && docker-php-ext-configure zip

RUN apt-get update -y

RUN apt-get install -y zip unzip

RUN apt autoremove -y && apt autoclean -y && apt-get upgrade -y

RUN apt-get install -y --no-install-recommends --no-install-suggests \
   git-core

RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev libonig-dev

RUN apt-get install libpng-dev -y

RUN apt-get update

RUN docker-php-ext-install gd && docker-php-ext-install -j$(nproc) gd && docker-php-ext-configure gd

WORKDIR /var/www/api

COPY composer.json ./

#COPY composer.lock ./

COPY . .

RUN composer install

EXPOSE 9000 80