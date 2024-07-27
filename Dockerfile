# Usar a imagem oficial do PHP como base
FROM php:8.2-fpm

# Definir o diretório de trabalho
WORKDIR /var/www

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

# Instalar extensões do PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar os arquivos do projeto para o container
COPY . .

# Instalar dependências do Laravel
RUN composer install --no-scripts --no-autoloader

# Expor a porta 9000
EXPOSE 9000

# Comando para rodar o PHP-FPM
CMD ["php-fpm"]