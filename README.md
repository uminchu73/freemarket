# coachtechフリマ

## 環境構築

#### リポジトリをクローン


```
git clone git@github.com:uminchu73/freemarket.git
```

```
docker-compose up -d --build
```

#### Laravel パッケージのダウンロード

```
docker-compose exec php bash
```

```
composer install
```

#### .env ファイルの作成

```
cp .env.example .env
```

```
docker-compose exec php bash
```

```
php artisan key:generate
```

#### .env ファイルの修正

DB_HOST=mysql

DB_DATABASE=laravel_db

DB_USERNAME=laravel_user

DB_PASSWORD=laravel_pass

###### Stripe（購入機能用）で追加するコード

STRIPE_KEY=your_stripe_public_key

STRIPE_SECRET=your_stripe_secret_key




#### マイグレーション・シーディングを実行

```
php artisan migrate --seed
```
テーブルを作成し、ダミーデータを投入します。


##### 初期ログイン情報

メール：user@example.com

パスワード：password123


## 使用技術（実行環境）

フレームワーク：Laravel Framework 8.83.29

言語：PHP 7.4.9

Webサーバー：Nginx v1.21.1

データベース：MySQL v8.0.26



## ER図

![coachtechフリマER図](src/assets/images/er.svg)


## URL

アプリケーション：http://localhost


phpMyAdmin：http://localhost:8080


