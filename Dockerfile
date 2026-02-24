FROM php:8.2-fpm

# Установка системных зависимостей (для Postgres, SQLite и работы с архивами)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libsqlite3-dev \
    zip \
    unzip \
    git \
    curl

# Установка расширений PHP
RUN docker-php-ext-install pdo pdo_pgsql pdo_sqlite

# Установка Composer (официальный образ)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Копируем проект
COPY . .

# Устанавливаем зависимости Laravel внутри контейнера
RUN composer install --no-interaction --optimize-autoloader

# Делаем скрипт race_test.sh исполняемым (если он есть)
RUN chmod +x race_test.sh || true

# Настройка прав на папки
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8000

# Запуск встроенного сервера Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
