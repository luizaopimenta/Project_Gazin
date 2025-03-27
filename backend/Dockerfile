FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd sockets \
    && apt-get clean && rm -rf /var/lib/apt/lists/*


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd sockets
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

WORKDIR /var/www/html

COPY . ./

RUN cp .env.example .env

RUN composer install --no-dev --optimize-autoloader

RUN php artisan key:generate

RUN php artisan migrate --force

RUN php artisan optimize

EXPOSE 9000

CMD ["php-fpm"]
