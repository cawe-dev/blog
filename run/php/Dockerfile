FROM php:8.5-fpm

ARG user=www
ARG uid=1000

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    zip \
    unzip \
    gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
    pdo_pgsql \
    mbstring \
    zip \
    exif \
    pcntl \
    gd \
    intl \
    bcmath \
    opcache

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN groupadd -g $uid $user || true && \
    useradd -u $uid -ms /bin/bash -g $user $user

WORKDIR /var/www/application

COPY --chown=$user:$user . /var/www/application

RUN chown -R $user:$user /var/www/application/storage /var/www/application/bootstrap/cache && \
    chmod -R 775 /var/www/application/storage /var/www/application/bootstrap/cache

USER $user

EXPOSE 9000
CMD ["php-fpm"]