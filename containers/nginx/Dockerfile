FROM ubuntu:latest
LABEL maintainer="timved@mail.ru"

RUN apt-get update && apt-get upgrade -y && apt-get install nginx -y

RUN mkdir /var/www/backend

ENV nginx_vhost /etc/nginx/sites-available/default
ENV nginx_conf /etc/nginx/nginx.conf

COPY vhost.conf ${nginx_vhost}
RUN echo "nginx-linux"

VOLUME ["/var/www/backend"]
WORKDIR /var/www/


EXPOSE 80
EXPOSE 443
