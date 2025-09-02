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




#### マイグレーション・シーディングを実行

```
php artisan migrate --seed
```
テーブルを作成し、ダミーデータを投入します。


##### 初期ログイン情報

メール：user@example.com

パスワード：password123

### Stripe 決済テスト方法

このプロジェクトでは、Stripeのテストモードを使って決済処理の動作確認を行います。

#### 1. テストモードの有効化
1. Stripeダッシュボードにログイン
2. 左下の「View test data」をONにする
3. テスト用APIキー（Publishable key、Secret key）を確認する

#### 2. APIキーの設定
プロジェクトの `.env` にテスト用キーを設定します：

```env
STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key
```

#### 3. テスト用カード番号

支払い成功：4242 4242 4242 4242

支払い失敗：4000 0000 0000 9995

有効期限、CVC、ZIPは任意

#### 4. 決済テスト
1. フロントエンドで商品購入画面を開く

2. 上記のテストカード情報を入力

3. 購入ボタンを押して決済処理を実行

#### 5. 注意点

テストモードでは実際にお金は動きません

本番モードのAPIキーを間違って設定しないよう注意


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


