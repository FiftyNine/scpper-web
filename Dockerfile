FROM php:7.4-apache
EXPOSE 80

ENV APACHE_DOCUMENT_ROOT /var/www/scpper/public

RUN apt update
RUN apt install git -y
RUN apt-get install -y \
         libzip-dev \
         && docker-php-ext-install zip \
         && docker-php-ext-install pdo_mysql

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN a2enmod rewrite
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
WORKDIR /var/www/scpper
RUN chown www-data:www-data /var/www/scpper
RUN chmod 777 /var/www/scpper 
COPY . .
RUN mv config/autoload/local.environment config/autoload/local.php
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer update