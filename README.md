# Apache + PHP 8.5 + PHP-FPM 開発環境

Apache が HTTP リクエストを受け、PHP ファイルを PHP-FPM コンテナに転送して実行します。
PHP-FPM イメージには CLI の PHP も含まれています。

## 起動

Git、Docker Engine、Docker Compose v2 が必要です。
Windows では Docker Desktop の WSL 連携を有効にし、WSL の Linux ファイルシステム上へ clone してください。
事前に `docker version` で Server 情報が表示されることと、`docker compose version` が成功することを確認します。

初めて取得する場合は、実際のリポジトリ URL に置き換えて実行します。

```bash
git clone <リポジトリURL> docker-php
cd docker-php
```

プロジェクトのルートで起動します。ローカルの 8080 ポートが空いている必要があります。

```bash
docker compose build --pull
docker compose up -d
```

http://localhost:8080 にアクセスすると PHP のバージョンと `SAPI: fpm-fcgi` が表示されます。
`public/` と `app/` 内のファイルを編集すると、再ビルドせずに反映されます。

`php:8.5-fpm` は PHP 8.5系の安定版を追従する公式タグです。
最新版へ更新するときも、上記のビルド・起動コマンドを実行してください。
起動中のコンテナが自動更新されるわけではありません。
Apache の `httpd:2.4` も同様に 2.4系の更新を追従します。
そのため、取得時期によって PHP・Apache・SQLite の細かいバージョンは変わる場合があります。

起動後の確認例（バージョン番号は環境によって変わります）：

```text
Apache + PHP-FPM が動作しています。
PHP version: 8.5.12
SAPI: fpm-fcgi
SQLite version: 3.46.1
```

## 確認・停止

```bash
# CLI で PHP のバージョンを確認
docker compose exec php php -v

# PHP-FPM と Apache の設定を確認
docker compose exec php php-fpm -t
docker compose exec apache httpd -t

# HTTP 応答を確認
curl --fail http://localhost:8080

# ログを確認
docker compose logs -f

# 停止・コンテナ削除
docker compose down
```

## ファイル構成

```text
docker-php/
├── compose.yaml
├── docker/
│   ├── apache/
│   │   ├── Dockerfile           # Apache 2.4
│   │   └── php-fpm.conf         # 公開ディレクトリとFastCGI転送設定
│   └── php/
│       └── Dockerfile           # PHP 8.5 / PHP-FPM、開発用php.ini
├── public/
│   └── index.php                # 公開する動作確認ページ
├── app/
│   └── database.php             # 公開領域外のSQLite接続設定
├── .dockerignore
├── .gitignore
└── README.md
```

ページ・CSS・JavaScript・画像は `public/` に、DB操作や共通テンプレートなどの内部処理は `app/` に追加します。
`public/` はコンテナ内の `/var/www/html`、`app/` は PHP コンテナ内の `/var/www/app` に対応します。

旧構成から更新した場合は、プロジェクトのルートで再ビルド・再作成してください。

```bash
docker compose up -d --build
```

PHP-FPM の 9000 ポートはホストへ公開せず、コンテナ間で接続します。
この設定はローカル開発用です。

## SQLite

PHP 標準の PDO_SQLITE を使用し、DB サーバー用コンテナは追加しません。
確認ページへの初回アクセスで `/var/lib/app/app.sqlite` を作成します。
接続設定は `app/database.php` にあります。
clone 先では新しい DB が作成されます。既存環境の DB データや Docker ボリュームは Git では共有されません。

DB は `sqlite-data` 名前付きボリュームに保存し、Apache にはマウントしません。
`docker compose down` では残りますが、`docker compose down -v` では削除されます。
SQLite は同時書き込みが多い用途では待ちが発生するため、規模に応じて MySQL 等を検討してください。
