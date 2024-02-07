###
# ・このDockerファイルはheroku container registoryにpushするイメージをbuildする本番環境用
# のDockerfileである。
# ・ローカル開発環境ではこのファイルは不要であるので注意されたし。
# ・./docker/php/に作成したかったが
# COPY . /var/www/html/strii-backendのCopy句が
# 想定通りにコピーされないためこの階層に作成している
###

FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update \
  && apt-get -y install libicu-dev libonig-dev libzip-dev unzip locales vim zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev libwebp-dev \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* \
  && locale-gen en_US.UTF-8 \
  && localedef -f UTF-8 -i en_US en_US.UTF-8 \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install intl pdo_mysql zip bcmath gd exif \
  && a2enmod rewrite

# 必要なフォルダ・ファイルのみをコピー(本番環境に上げたくないものを省いてコピー)
COPY app/ ./app/
COPY bootstrap/ ./bootstrap/
COPY config/ ./config/
COPY database/ ./database/
COPY lang/ ./lang/
COPY public/ ./public/
COPY resources/ ./resources/
COPY routes/ ./routes/
COPY storage/ ./storage/
COPY tests/ ./tests/
COPY vendor/ ./vendor/
COPY .editorconfig .gitattributes .gitignore apache-start.sh artisan composer.json composer.lock docker-compose.yml Dockerfile heroku.yml phpunit.xml README.md  ./

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# publicフォルダとstorageフォルダのパーミッションを設定
RUN chown -R www-data:www-data /var/www/html/public /var/www/html/storage
RUN chmod -R 775 /var/www/html/public /var/www/html/storage

# 起動スクリプトをコピー
# herokuのbuildではherokuのdynoの環境変数を参照できないためシェルスクリプトで定義し、
# コンテナ起動後で環境変数を参照できる状態でコマンドを実行させるため
COPY apache-start.sh /usr/local/bin/

# 起動スクリプトを実行可能にする
RUN chmod +x /usr/local/bin/apache-start.sh

# コンテナ起動時に起動スクリプトを実行する
CMD ["/usr/local/bin/apache-start.sh"]
