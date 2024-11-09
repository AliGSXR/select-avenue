# Utilisez une image PHP comme base
FROM php:8.1-fpm

# Installez les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

# Installez Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurez le répertoire de travail
WORKDIR /app

# Copiez le contenu de votre application dans le conteneur
COPY . .

# Installez les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Exposez le port 9000
EXPOSE 9000

# Commande pour démarrer PHP-FPM
CMD ["php-fpm"]