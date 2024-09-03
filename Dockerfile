# Use uma imagem PHP-FPM com versão 8.2
FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www

# Copiar arquivos para o contêiner
COPY . .

# Instalar dependências do Laravel e do Node.js
RUN npm install
RUN composer install

# Expor a porta 9000 para o PHP-FPM
EXPOSE 9000

# Expor a porta 5173 para o Vite
EXPOSE 5173

# Executar o servidor do Laravel e o Vite no início
CMD php artisan serve --host=0.0.0.0 --port=8000 & npm run dev
