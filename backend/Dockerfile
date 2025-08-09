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

# Définis le répertoire de travail
WORKDIR /var/www/html

# Copie le code de l'application
COPY . .

# Copie le fichier .env de production
RUN cp -n .env.prod .env

# Installe les dépendances PHP et compile les assets
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install
RUN npm run build

# Crée le script de démarrage pour Railway
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Starting Laravel API on Railway..."\n\
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
# Générer la clé si nécessaire\n\
php artisan key:generate --force\n\
\n\
# Cache optimisations\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Storage link pour les fichiers\n\
php artisan storage:link\n\
\n\
# Utiliser le port fourni par Railway\n\
PORT=${PORT:-8000}\n\
echo "Starting Laravel API on port $PORT"\n\
php artisan serve --host=0.0.0.0 --port=$PORT' > /usr/local/bin/start.sh

# Rends le script exécutable
RUN chmod +x /usr/local/bin/start.sh

# Définit les permissions
RUN chown -R www-data:www-data /var/www/html

# Expose le port de l'application
EXPOSE $PORT

# Commande de démarrage
CMD ["/usr/local/bin/start.sh"]