FROM php:8.3-fpm

# Установка необходимых зависимостей
RUN apt-get update && apt-get install -y \
      apt-utils \
      supervisor \
      libpq-dev \
      libpng-dev \
      gettext \
      libzip-dev \
      zip unzip \
      libxml2-dev \
      git \
      wget \
      build-essential \
      zlib1g-dev \
      libssl-dev && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Сборка и установка библиотеки librdkafka
ENV LIBRDKAFKA_VERSION=2.6.1
RUN wget https://github.com/edenhill/librdkafka/archive/refs/tags/v${LIBRDKAFKA_VERSION}.tar.gz && \
    tar -xvf v${LIBRDKAFKA_VERSION}.tar.gz && \
    cd librdkafka-${LIBRDKAFKA_VERSION} && \
    ./configure --prefix=/usr && make && make install && \
    cd .. && rm -rf librdkafka-${LIBRDKAFKA_VERSION} v${LIBRDKAFKA_VERSION}.tar.gz

# Установка расширения rdkafka через PECL
RUN pecl install rdkafka && docker-php-ext-enable rdkafka

# Install xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Установка других PHP-расширений
RUN docker-php-ext-install pdo_mysql pdo_pgsql bcmath opcache gd zip soap pcntl && \
    docker-php-ext-enable pdo_mysql pdo_pgsql

# Копирование файла конфигурации PHP
COPY ./php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Установка Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

# Установка рабочей директории
WORKDIR /var/www
