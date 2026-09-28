FROM php:8.2-apache

# Install dependensi PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql

# Memperbaiki konflik MPM Apache di Docker
RUN a2dismod mpm_event && a2enmod mpm_prefork

# Konfigurasi port Apache agar membaca port dari Railway
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Salin file proyek
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html
