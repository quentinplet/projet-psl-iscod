# Utilise une image de base PHP avec l'extension CLI
FROM php:8.3-cli

# Installe les dépendances système et les extensions PHP
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    default-mysql-client \
    nodejs \
    npm \
    && docker-php-ext-install bcmath ctype fileinfo mbstring pdo_mysql xml zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Augmente la limite mémoire pour Composer
ENV COMPOSER_MEMORY_LIMIT=-1

# Définis le répertoire de travail
WORKDIR /var/www/html

# Copie d'abord les fichiers de dépendances
COPY composer.json composer.lock* ./

# Installe les dépendances PHP
RUN composer install --no-interaction --optimize-autoloader --no-dev --verbose || \
    composer install --no-interaction --optimize-autoloader --no-dev --no-scripts --verbose

# Copie le reste du code
COPY . .

# Copie le fichier .env
RUN cp -n .env.prod .env

# Crée les répertoires nécessaires
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Installe et compile les assets
RUN npm install
RUN npm run build

# Crée le script de démarrage
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Starting Laravel API on Railway..."\n\
\n\
# Générer la clé Laravel si nécessaire\n\
if ! grep -q "APP_KEY=base64:" .env || [ -z "$(grep APP_KEY .env | cut -d= -f2)" ]; then\n\
    echo "Generating Laravel key..."\n\
    php artisan key:generate --force\n\
fi\n\
\n\
# Attendre la base de données\n\
if [ -n "$DATABASE_URL" ] || [ -n "$MYSQL_URL" ]; then\n\
    echo "Waiting for database..."\n\
    for i in {1..30}; do\n\
        if php artisan migrate:status --force >/dev/null 2>&1; then\n\
            echo "Database is ready!"\n\
            break\n\
        fi\n\
        echo "Database not ready, waiting... ($i/30)"\n\
        sleep 3\n\
    done\n\
    \n\
    # Exécuter les migrations\n\
    php artisan migrate --force\n\
fi\n\
\n\
# Cache optimisations\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Storage link\n\
php artisan storage:link\n\
\n\
# Démarrer le serveur\n\
PORT=${PORT:-8000}\n\
echo "Starting Laravel API on port $PORT"\n\
php artisan serve --host=0.0.0.0 --port=$PORT' > /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE $PORT

CMD ["/usr/local/bin/start.sh"]