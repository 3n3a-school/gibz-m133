FROM php:8.0-apache
RUN a2enmod rewrite
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y libicu-dev

# PHP extensions
RUN \
    docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure mysqli --with-mysqli=mysqlnd \
    && docker-php-ext-install pdo_mysql
    
# PHP International Library
RUN \
    docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-enable intl