FROM golang:1.12.5-stretch as runner
RUN go get github.com/spiral/roadrunner/...
RUN cd /go/src/github.com/spiral/roadrunner/ && make

FROM composer:latest as composer
FROM php:7.3.5-stretch as php
WORKDIR /app
RUN apt-get update && apt-get install -y git zip unzip libpq-dev
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pgsql pdo_pgsql
COPY ./ /app
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer install
COPY --from=runner /go/src/github.com/spiral/roadrunner/rr /app/rr
RUN chmod +x /app/rr
ENTRYPOINT /app/rr serve
