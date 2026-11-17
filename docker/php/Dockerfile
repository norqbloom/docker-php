FROM php:8.5-fpm

WORKDIR /var/www/html

RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN mkdir -p /var/lib/app /var/www/app \
    && chown www-data:www-data /var/lib/app \
    && php -r 'exit(extension_loaded("pdo_sqlite") ? 0 : 1);'

COPY app/ /var/www/app/
COPY public/ /var/www/html/
