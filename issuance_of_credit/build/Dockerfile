FROM php:8.3-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash

# Копирование кода приложения
COPY . /var/www/html

# Установка зависимостей приложения
RUN composer install --no-scripts --no-autoloader

# Разрешение для записи
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor

# Запуск сборки
RUN composer dump-autoload --optimize

WORKDIR /var/www/html
