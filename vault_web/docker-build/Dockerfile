FROM composer:2 AS builder

WORKDIR /app

COPY composer.json ./

RUN composer install

FROM php:8.5-apache

WORKDIR /var/www/html/

COPY --from=builder /app/ .

COPY 001-vault.conf /etc/apache2/sites-available

COPY docker-php-vault.ini /usr/local/etc/php/conf.d/

ENV TZ=America/Los_Angeles

# Adjusts LogFormat to capture real IP from Traefik
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    echo 'Listen 89' >> /etc/apache2/ports.conf && \
    sed -i '/^LogFormat "%v:%p %h/s/%h/%{X-Forwarded-For}i/' /etc/apache2/apache2.conf && \
    apt-get update && \
    # apt-get install -y  --no-install-recommends ffmpeg ssl-cert libzip-dev libpng-dev && \
    apt-get install -y  --no-install-recommends tzdata ffmpeg libzip-dev libpng-dev && \
    rm -r /var/lib/apt/lists/* && \
    ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone && \
    # a2enmod ssl && \
    a2dissite 000-default.conf && \
    a2ensite 001-vault.conf && \
    if command -v a2enmod >/dev/null 2>&1; then \
        a2enmod rewrite headers \
    ;fi && \
    docker-php-ext-install pdo pdo_mysql zip gd exif

# EXPOSE 80 89 443