# Usa uma imagem oficial do PHP com Apache como base
FROM php:8.2

# Instala extensões do PHP necessárias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia todos os arquivos para dentro do container
COPY . .

# Concede permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Expõe a porta do artisan serve
EXPOSE 8000

# Roda migrations, seeders e inicia o servidor Laravel
CMD php artisan migrate:fresh --seed && php artisan serve --host=0.0.0.0 --port=8000
