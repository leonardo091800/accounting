FROM php:fpm-alpine3.19

COPY src/* /var/www/html

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm", "-F", "-R"]
