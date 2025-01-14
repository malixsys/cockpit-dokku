FROM php:7-apache

RUN apt-get update \
    && apt-get install -y \
		wget zip unzip \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        sqlite3 libsqlite3-dev \
        libssl-dev \
    && pecl install mongodb \
    && pecl install redis

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) iconv gd pdo zip opcache pdo_sqlite \
    && a2enmod rewrite expires

RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini
RUN echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

RUN chown -R www-data:www-data /var/www/html

COPY . /var/www/html
RUN mkdir -p /var/www/html/config/
RUN mv docker-config.php /var/www/html/config/config.php
RUN mv uploads.ini /usr/local/etc/php/conf.d/uploads.ini
RUN chmod -R 777 /var/www/html/storage
VOLUME /var/www/html/storage

CMD ["apache2-foreground"]
