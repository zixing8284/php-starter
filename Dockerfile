FROM composer:2 AS composer

FROM php:8.4-fpm-alpine

ARG MIRROR_CN=false

RUN if [ "$MIRROR_CN" = "true" ]; then \
        sed -i 's/dl-cdn.alpinelinux.org/mirrors.tuna.tsinghua.edu.cn/g' /etc/apk/repositories; \
    fi

RUN apk add --no-cache nginx supervisor sqlite-dev

RUN docker-php-ext-install pdo_sqlite

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN if [ "$MIRROR_CN" = "true" ]; then \
        composer config -g repos.packagist composer https://mirrors.tuna.tsinghua.edu.cn/composer/; \
    fi

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/supervisord.conf /etc/supervisord.conf

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

RUN mkdir -p database && chown www-data:www-data database

ENV SQLITE_DB_PATH=/var/www/html/database/app.db

EXPOSE 80

CMD ["supervisord", "-c", "/etc/supervisord.conf"]
