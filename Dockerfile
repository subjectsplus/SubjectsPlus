FROM php:7.4.6-apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN apt-get update && apt-get install -y zlib1g zlib1g-dev libpng-dev
RUN docker-php-ext-install pdo_mysql mysqli gettext gd
RUN a2enmod headers rewrite
COPY . /var/www/html/
COPY ./.docker/000-default.conf /etc/apache2/sites-enabled/000-default.conf
RUN apache2ctl restart