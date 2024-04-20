FROM php:8.2-apache-buster
LABEL maintainer="Matias <matiasgonzaloacosta@gmail.com>"

# set locales
RUN apt-get update \
    && apt-get install -y locales \
    && echo "es_AR.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen \
    && echo "es es_AR.UTF-8" >> /etc/locale.alias

# install extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    vim-tiny \
    nano \
    exiftool \
    libzip-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip xml exif soap gd \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer \
    && echo "alias vim=vi" >> ~/.bashrc \
    && echo "alias art='php artisan'" >> ~/.bashrc \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && ln -sf /dev/stdout /var/log/apache2/access.log \
    && ln -sf /dev/stderr /var/log/apache2/error.log

# enable apache modules
RUN a2enmod rewrite && a2enmod

# activate php error logs
RUN echo php_flag log_errors On > /etc/apache2/conf-enabled/php-log-errors.conf

# point default site to public directory
RUN sed -i 's/www\/html/www\/html\/public/g' /etc/apache2/sites-enabled/000-default.conf

# Copy files
ADD . /var/www/html

WORKDIR /var/www/html

RUN touch storage/logs/laravel.log \
    && chown -R www-data:www-data /var/www/html

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev

EXPOSE 80
