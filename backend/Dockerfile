FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libonig-dev libxml2-dev libzip-dev unzip git curl nodejs npm wget \
    && docker-php-ext-install bcmath ctype fileinfo mbstring pdo_mysql xml zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN cp -n .env.prod .env

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN php artisan key:generate

RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
