FROM php:8.3-apache

# 1. Instalar dependencias del sistema y herramientas necesarias 
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    unzip \
    git

# 2. Instalar extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql

# 3. INSTALAR COMPOSER
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Configuraciones de Apache
RUN a2enmod rewrite headers
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# 🔧 CAMBIAR DOCUMENTROOT DE APACHE A /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf
# 5. Definir directorio de trabajo
WORKDIR /var/www/html

# 6. ESTRATEGIA DE CACHÉ PARA COMPOSER
COPY src/composer.json src/composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-dev

# 7. Copiar el resto del código de la aplicación
COPY src/ .

# 8. Generar el autoloader optimizado
RUN composer dump-autoload --optimize

# 9. Permisos finales
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html