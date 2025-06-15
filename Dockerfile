FROM php:8.1-apache

# Instalar extensiones requeridas
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev libzip-dev libonig-dev libxml2-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el proyecto
COPY . /var/www/html/

# Cambiar el directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar Apache para que apunte a la carpeta web/
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf
