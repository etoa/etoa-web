FROM php:8.0-fpm

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN pecl install apcu && docker-php-ext-enable apcu

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
