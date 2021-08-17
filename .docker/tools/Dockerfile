FROM composer as composer

FROM dockerhub.greensight.ru/ensi-tech/php-base-image:8.0-2021jun23-1

RUN apk add npm git openjdk8-jre-base

COPY --from=composer /usr/bin/composer /usr/bin/composer
