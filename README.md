# coachtechフリマ

## 環境構築

### 1. リポジトリをクローン

```
git clone git@github.com:uminchu73/freemarket.git
```
```
docker-compose up -d --build
```

### 2. Laravel パッケージのダウンロード

```
docker-compose exec php bash
```
```
composer install
```

### 3. 環境変数ファイルの設定

```
cp .env.example .env
```
```
php artisan key:generate
```

`.env` を編集し、以下を設定します：

```
DB_HOST=mysql

DB_DATABASE=laravel_db

DB_USERNAME=laravel_user

DB_PASSWORD=laravel_pass
```

Stripeを利用する場合は、さらに以下を追記してください：
```

STRIPE_KEY=your_stripe_public_key

STRIPE_SECRET=your_stripe_secret_key
```

### 4. マイグレーション・シーディングを実行

```
php artisan migrate --seed
```
テーブルを作成し、ダミーデータを投入します。

### 5. ストレージのシンボリックリンク作成

```
php artisan storage:link
```

### 6. テスト用データベース作成

```
docker-compose exec mysql bash
```
```
mysql -u root -p
```
パスワードは `docker-compose.yml` 内の `MYSQL_ROOT_PASSWORD` に設定されている値を使用してください。
```
CREATE DATABASE demo_test;
```

## 初期ログイン情報

メールアドレス：`user@example.com`

パスワード：`password123`

## Stripe 決済テスト方法

このプロジェクトでは、Stripeのテストモードを使って決済処理の動作確認を行います。

### 1. テストモードの有効化
1. Stripeダッシュボードにログイン
2. 左下の「View test data」をONにする
3. テスト用APIキー（Publishable key、Secret key）を確認する

### 2. APIキーの設定
プロジェクトの `.env` にテスト用キーを設定します：


### 3. テスト用カード番号

支払い成功：`4242 4242 4242 4242`

支払い失敗：`4000 0000 0000 9995`

※有効期限、CVC、ZIPは任意で入力してください。

### 4. 決済テスト実行
1. 商品購入画面を開く

2. 上記のテストカード情報を入力

3. 購入ボタンを押して決済処理を実行

※ テストモードでは実際にお金は動きません。本番モードのAPIキーを間違って設定しないよう注意。


## 使用技術（実行環境）

フレームワーク：Laravel Framework 8.83.29

言語：PHP 8.1.33

Webサーバー：Nginx v1.21.1

データベース：MySQL v8.0.26



## ER図

![coachtechフリマER図](src/assets/images/er.svg)


## URL

アプリケーション：http://localhost


phpMyAdmin：http://localhost:8080


