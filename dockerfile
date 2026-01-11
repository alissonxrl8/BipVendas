FROM php:8.2-apache

RUN apt-get update && apt-get install -y unzip git
RUN docker-php-ext-install pdo

RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
