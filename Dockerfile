FROM composer as builder
COPY ./src/composer.* /src/
WORKDIR /src/
RUN composer install

FROM php:8.0-apache as base

COPY ./src /var/www/html
COPY --from=builder /src/vendor /var/www/html/vendor
