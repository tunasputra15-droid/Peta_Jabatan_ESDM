FROM php:8.2-apache

# Install dependensi sistem untuk PostgreSQL (libpq-dev)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install ekstensi PDO PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Salin seluruh file proyek ke dalam web server
COPY . /var/www/html/

# Ubah hak akses folder
RUN chown -R www-data:www-data /var/www/html
