FROM php:8.2-apache

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip

RUN docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto
COPY . /var/www/html

WORKDIR /var/www/html

# Instalar Laravel
RUN composer install

# Permisos
RUN chmod -R 777 storage bootstrap/cache

# Apache config
RUN a2enmod rewrite

EXPOSE 7860

CMD ["apache2-foreground"]