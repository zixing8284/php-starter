FROM composer:2 AS composer

FROM php:8.4-fpm-alpine

RUN apk add --no-cache nginx supervisor sqlite-dev

RUN docker-php-ext-install pdo_sqlite

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

RUN mkdir -p data && chown www-data:www-data data

EXPOSE 80

CMD ["supervisord", "-c", "/etc/supervisord.conf"]
