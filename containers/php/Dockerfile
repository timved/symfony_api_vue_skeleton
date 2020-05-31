FROM ubuntu:bionic
LABEL maintainer="timved@mail.ru"

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get upgrade -y && apt-get install unzip git php7.2-fpm php7.2-mysql php7.2-mongodb php7.2-mbstring php7.2-redis php7.2-json php7.2-xml php7.2-xdebug php7.2-gd php7.2-intl php7.2-curl php7.2-zip php7.2-pgsql php-pear php-dev -y
RUN pecl install mongodb
RUN mkdir /run/php
RUN mkdir -p /var/www/backend

COPY php.ini /etc/php/7.2/fpm/php.ini
COPY xdebug.ini /etc/php/7.2/mods-available/xdebug.ini
COPY mongodb.ini /etc/php/7.2/mods-available/mongodb.ini
COPY pool.conf /etc/php/7.2/fpm/pool.d/www.conf

WORKDIR /var/www/backend

# Install composer
RUN curl -sS https://getcomposer.org/installer | php --  --filename=composer --install-dir=/usr/local/bin && echo "alias composer='composer'" >> /root/.bashrc

CMD ["php-fpm7.2 -F"]

EXPOSE 9072