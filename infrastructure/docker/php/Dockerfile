FROM php:8.4-cli-alpine

# System dependencies required by the PHP extensions used by the application.
RUN apk add --no-cache \
        bash \
        git \
        unzip \
        libpng-dev \
        libzip-dev \
        icu-dev \
        libxml2-dev \
        oniguruma-dev \
        postgresql-dev \
        postgresql-client \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        ctype \
        fileinfo \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_mysql \
        pdo_pgsql \
        zip \
    && php -m | grep -q pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install PHP dependencies first so the layer is cached between code changes.
# --no-scripts: the post-autoload-dump script runs `artisan`, which does not
# exist until the application files are copied below.
COPY backend/composer.json backend/composer.lock /app/
RUN composer install --no-interaction --prefer-dist --no-progress --no-scripts \
    && composer clear-cache

COPY backend/ /app/

# Regenerate the autoloader now that the application exists, which also runs
# package discovery.
RUN composer dump-autoload --optimize --no-interaction

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app", "/app/router.php"]
