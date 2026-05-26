# Usamos una imagen oficial de PHP súper ligera
FROM php:8.4-cli

# Instalamos las herramientas necesarias y la extensión de PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev zip unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Nos movemos a la carpeta de la app
WORKDIR /app

# Copiamos todo tu código de Laravel dentro del servidor
COPY . .

# Instalamos las dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev

# Damos permisos a las carpetas de almacenamiento (Vital en Laravel)
RUN chmod -R 777 storage bootstrap/cache

# Levantamos el servidor de Laravel
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}