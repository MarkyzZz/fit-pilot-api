FROM alpine:3.21

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
# PHP 8.4 — only what's needed
# ──────────────────────────────────────────────
RUN apk add --no-cache \
    php84 \
    php84-fpm \
    php84-pgsql \
    php84-pdo \
    php84-pdo_pgsql \
    php84-sqlite3 \
    php84-pdo_sqlite \
    php84-redis \
    php84-gd \
    php84-curl \
    php84-mbstring \
    php84-xml \
    php84-zip \
    php84-bcmath \
    php84-intl \
    php84-openssl \
    php84-tokenizer \
    php84-phar \
    php84-dom \
    php84-xmlwriter \
    php84-xmlreader \
    php84-ctype \
    php84-fileinfo \
    php84-session \
    php84-simplexml \
    && ln -sf /usr/bin/php84 /usr/bin/php

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
COPY php.ini /etc/php84/conf.d/99-fp.ini
COPY nginx.conf /etc/nginx/nginx.conf
COPY php-fpm.conf /etc/php84/php-fpm.d/www.conf
RUN chmod 755 /usr/local/bin/start-container

EXPOSE 80/tcp

ENTRYPOINT ["start-container"]

USER fp
