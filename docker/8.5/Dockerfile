FROM alpine:3.24

ARG WWWGROUP=1001
ARG WWWUSER=1001

WORKDIR /var/www/html

ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# ──────────────────────────────────────────────
# System packages
# ──────────────────────────────────────────────
RUN apk add --no-cache \
    bash curl ca-certificates git zip unzip \
    sqlite supervisor su-exec libcap shadow nano \
    icu-libs libzip libpq libxml2 oniguruma \
    freetype libjpeg-turbo libpng libgd \
    nginx

# ──────────────────────────────────────────────
# PHP 8.5 — only what's needed
# ──────────────────────────────────────────────
RUN apk add --no-cache \
    php85 \
    php85-fpm \
    php85-pgsql \
    php85-pdo \
    php85-pdo_pgsql \
    php85-sqlite3 \
    php85-pdo_sqlite \
    php85-redis \
    php85-gd \
    php85-curl \
    php85-mbstring \
    php85-xml \
    php85-zip \
    php85-bcmath \
    php85-intl \
    php85-openssl \
    php85-tokenizer \
    php85-phar \
    php85-dom \
    php85-xmlwriter \
    php85-xmlreader \
    php85-ctype \
    php85-fileinfo \
    php85-session \
    php85-simplexml \
    && ln -sf /usr/bin/php85 /usr/bin/php

# ──────────────────────────────────────────────
# Composer
# ──────────────────────────────────────────────
RUN curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# ──────────────────────────────────────────────
# Node.js + npm + pnpm
# ──────────────────────────────────────────────
RUN apk add --no-cache nodejs npm \
    && npm install -g pnpm

# ──────────────────────────────────────────────
# PostgreSQL client
# ──────────────────────────────────────────────
RUN apk add --no-cache postgresql16-client

# ──────────────────────────────────────────────
# Create fp user + composer cache dir
# ──────────────────────────────────────────────
RUN addgroup -g ${WWWGROUP} fp \
    && adduser -s /bin/bash -G fp -u ${WWWUSER} -D fp \
    && mkdir -p /.composer && chmod -R ugo+rw /.composer \
    && mkdir -p /var/log/supervisor \
    && git config --global --add safe.directory /var/www/html

RUN chmod u+s /sbin/su-exec

# ──────────────────────────────────────────────
# Config files
# ──────────────────────────────────────────────
COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /etc/php85/conf.d/99-fp.ini
COPY nginx.conf /etc/nginx/nginx.conf
COPY php-fpm.conf /etc/php85/php-fpm.d/www.conf
RUN chmod 755 /usr/local/bin/start-container

EXPOSE 80/tcp

ENTRYPOINT ["start-container"]

USER fp
