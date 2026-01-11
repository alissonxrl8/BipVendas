FROM php:8.2-apache

# Instalar dependências do sistema necessárias para PDO e SQLite
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libsqlite3-dev \
    sqlite3 \
    pkg-config \
    zlib1g-dev \
    libonig-dev \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite zip

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Apache servindo Laravel public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Definir diretório do app
WORKDIR /var/www/html

# Copiar código
COPY . .

# Instalar composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependências Laravel
RUN composer install --no-dev --optimize-autoloader

# Permissões corretas
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Criar .env se não existir
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Gerar APP_KEY
RUN php artisan key:generate

EXPOSE 80
