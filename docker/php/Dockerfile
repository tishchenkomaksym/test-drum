FROM php:8.2-fpm

RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
            curl \
            libmemcached-dev \
            libz-dev \
            libpq-dev \
            libjpeg-dev \
            libpng-dev \
            libfreetype6-dev \
            libssl-dev \
            libwebp-dev \
            libxpm-dev \
            libmcrypt-dev \
            libonig-dev; \
    rm -rf /var/lib/apt/lists/*


# PHP extension
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install calendar
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install sockets
RUN pecl install igbinary && docker-php-ext-enable igbinary
RUN docker-php-ext-install  opcache pdo pdo_mysql
RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN set -eux; \
    # Install the PHP gd library
    docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-xpm \
            --with-freetype; \
    docker-php-ext-install gd; \
    php -r 'var_dump(gd_info());'

RUN docker-php-ext-install exif && docker-php-ext-enable exif
RUN apt update && apt install sqlite3
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN apt-get update && apt-get install zip unzip

WORKDIR /var/www/html
