FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    curl \
    git \
    build-base \
    zlib-dev \
    oniguruma-dev \
    autoconf \
    bash

ARG PUID=1000
ARG PGID=1000
RUN apk add --no-cache shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

COPY ./ /app
WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint"]
