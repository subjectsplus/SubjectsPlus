FROM php:7.4.6-apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN apt-get update && apt-get install -y git zlib1g zlib1g-dev libpng-dev
RUN docker-php-ext-install pdo_mysql mysqli gettext gd
RUN a2enmod headers rewrite
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY . /var/www/html/
COPY ./.docker/000-default.conf /etc/apache2/sites-enabled/000-default.conf
RUN apache2ctl restart
