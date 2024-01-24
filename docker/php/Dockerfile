FROM php:8.2-apache

RUN apt-get update \
  && apt-get -y install git libicu-dev libonig-dev libzip-dev unzip locales vim zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev libwebp-dev \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* \
  && locale-gen en_US.UTF-8 \
  && localedef -f UTF-8 -i en_US en_US.UTF-8 \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install intl pdo_mysql zip bcmath gd exif \
  # composer config -g process-timeout 3600 && \
  # composer config -g repos.packagist composer https://packagist.org
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && a2enmod rewrite
  # HTTPS有効化
  # && apt-get -y install ssl-cert \
  # && make-ssl-cert generate-default-snakeoil \
  # && a2enmod ssl \
  # && a2ensite default-ssl \
  # && apachectl restart 

ENV \
  # TZ=UTC \
  # TZ=Asia/Tokyo \
  # locale日本語設定
  # LANG=ja_JP.UTF-8 \
  # LANGUAGE=ja_JP:ja 
  # LC_ALL=ja_JP.UTF-8 
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8 

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# 各種設定ファイルをコンテナの設定ファイルにコピー
COPY php.ini /usr/local/etc/php/
COPY apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf
# COPY apache/default-ssl.conf /etc/apache2/sites-enabled/default-ssl.conf
# COPY ssl_key/localhost-key.pem /etc/ssl/private/localhost-key.pem
# COPY ssl_key/localhost.pem /etc/ssl/private/localhost.pem

WORKDIR /var/www/html
