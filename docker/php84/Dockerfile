FROM php:8.4-fpm-alpine

# Обновляем пакеты и устанавливаем необходимые зависимости
# Устанавливаем системные зависимости
RUN apk add --no-cache \
    fcgi \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    oniguruma-dev \
    postgresql-dev \
    icu-dev \
    libxml2-dev \
    linux-headers \
    nodejs \
    npm \
    $PHPIZE_DEPS

# Устанавливаем расширения PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    mysqli \
    exif \
    pcntl \
    gd \
    intl \
    bcmath \
    opcache \
    zip \
    xml \
    soap

# Устанавливаем Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Копируем конфигурацию Xdebug
COPY conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Очистка кеша apk
RUN rm -rf /var/cache/apk/*

ENV PATH="$PATH:/root/.composer/vendor/bin"

WORKDIR /var/www/html

COPY entrypoint.sh /usr/local/bin/app-entrypoint
RUN sed -i 's/\r$//' /usr/local/bin/app-entrypoint && chmod +x /usr/local/bin/app-entrypoint
#RUN chmod +x /entrypoint.sh
#
ENTRYPOINT ["/usr/local/bin/app-entrypoint"]