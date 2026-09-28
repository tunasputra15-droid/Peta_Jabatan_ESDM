FROM php:8.2-cli

# Install dependensi PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql

# Salin seluruh file proyek ke dalam container
COPY . /app
WORKDIR /app

# Jalankan server menggunakan port dinamis dari Railway
CMD php -S 0.0.0.0:${PORT}
