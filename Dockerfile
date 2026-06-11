FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    unzip zip curl git \
    libzip-dev libicu-dev \
    locales \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && echo "es_MX.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen \
    && update-locale LANG=es_MX.UTF-8

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        intl pdo_mysql mysqli zip gd opcache

ENV LANG=es_MX.UTF-8
ENV LC_ALL=es_MX.UTF-8

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data writable

CMD ["php-fpm"]