#!/bin/bash

# herokuのbuildではherokuのdynoの環境変数を参照できないためシェルスクリプトで定義し、
# コンテナ起動後で環境変数を参照できる状態でコマンドを実行させるため

# Apacheの設定ファイルを更新する
# Apacheのポートが環境変数のPORTで自動で割り当てられるので設定を変更
sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf
sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/apache2.conf
sed -i "s/VirtualHost \*:80/VirtualHost \*:${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf

php artisan storage:link

# Apacheを起動する
apache2-foreground
