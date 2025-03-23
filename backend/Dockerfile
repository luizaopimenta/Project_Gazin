FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    unzip \
    git \
    curl \
    wget \
    libaio1 \
    libonig-dev \
    libpng-dev \
    zlib1g-dev \
    vim \
    zsh \
    xclip \
    procps \
    rsync \
    libpq-dev && \
    apt-get autoremove --yes && \
    rm -rf /var/lib/{apt,dpkg,cache,log} && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd sockets
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

WORKDIR /var/www/html

COPY . ./

RUN composer install --no-dev --optimize-autoloader

EXPOSE 9000

CMD ["php-fpm"]
