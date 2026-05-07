FROM composer:2 AS composer

FROM php:8.4-fpm-alpine

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories && \
    apk add --no-cache nginx supervisor sqlite-dev

RUN docker-php-ext-install pdo_sqlite

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/supervisord.conf /etc/supervisord.conf

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

RUN mkdir -p database && chown www-data:www-data database

EXPOSE 80

CMD ["supervisord", "-c", "/etc/supervisord.conf"]
