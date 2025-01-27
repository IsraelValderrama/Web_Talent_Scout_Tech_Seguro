# Usar la imagen base de PHP con Apache
FROM php:5.6-apache

# Actualizar los repositorios y eliminar los que no son necesarios
RUN sed -i 's|http://deb.debian.org/debian|http://archive.debian.org/debian|g' /etc/apt/sources.list \
    && sed -i '/deb.*security.*stretch/d' /etc/apt/sources.list \
    && sed -i '/deb.*stretch-updates/d' /etc/apt/sources.list

# Instalar las dependencias necesarias para SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar las extensiones de PHP para SQLite
RUN if ! php -m | grep -q 'pdo_sqlite'; then docker-php-ext-install pdo_sqlite sqlite3; fi

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]