FROM php:8.2-fpm-alpine

# Install packages
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash
RUN apk add --update linux-headers
RUN apk add --no-cache icu-dev
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# Xdebug
ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; then \
      pecl install xdebug && docker-php-ext-enable xdebug; \
    fi;

COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Postgres
RUN apk add --no-cache libpq-dev && docker-php-ext-install pdo_pgsql

# Nginx
RUN apk add --update --no-cache nginx
COPY ./nginx.conf /etc/nginx/
RUN chown -Rf www-data:www-data /var/lib/nginx

# Supervisor
RUN apk add --no-cache supervisor
COPY ./supervisord.conf /etc/supervisord.conf

# Source code
RUN chown www-data:www-data /var/www
COPY --chown=www-data:www-data ./ /var/www
WORKDIR /var/www

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG BUILD_MODE=dev

RUN if [ ${BUILD_MODE} = dev ]; then \
      export COMPOSER_ALLOW_SUPERUSER=1; \
      composer install --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader; \
      composer require symfony/runtime; \
      chown -R www-data:www-data /var/www/vendor/; \
    else \
      composer install --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader; \
    fi;

EXPOSE 8080

CMD ["/bin/sh", "./run.sh"]