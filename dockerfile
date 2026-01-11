FROM php:8.2-apache

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev

# Extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite zip

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Apontar Apache para /public do Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Pasta do app
WORKDIR /var/www/html

# Copiar projeto
COPY . .

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Permissões para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Criar .env se não existir
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Gerar APP_KEY
RUN php artisan key:generate

EXPOSE 80
