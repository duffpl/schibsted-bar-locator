FROM php:7.0.9-fpm
RUN docker-php-ext-install opcache && \
echo opcache.enable=1 >> /usr/local/etc/php/php.ini
RUN echo expose_php=0 >> /usr/local/etc/php/conf.d/extra.ini
