# BookShelf

## 主な機能

### 書籍管理

- 書籍一覧表示
- 書籍詳細表示
- 書籍登録
- 書籍編集
- 書籍削除
- キーワード検索
- ジャンル検索
- ISBN検索
- 書籍の並び替え

書籍一覧ページはゲストユーザーでも閲覧できます。

書籍の登録・編集・削除にはログインが必要です。

書籍の編集・削除についてはLaravel Policyによる認可処理を行っています。

---

### ISBN検索

ISBNを指定して書籍情報を検索できます。

```text
GET /books/isbn/{isbn}
```

外部APIから取得した書籍情報を利用して、書籍登録時の入力を補助します。

---

### ジャンル管理

- ジャンル一覧
- ジャンル詳細
- ジャンル登録
- ジャンル編集
- ジャンル削除
- 書籍とジャンルの紐付け

ジャンル機能はログインユーザーのみ利用できます。

---

### レビュー

書籍に対してレビューを投稿できます。

- レビュー投稿
- レビュー編集
- レビュー削除
- 1〜5段階評価
- レビューへの「いいね」

レビューの編集・削除には認可処理を使用しています。

---

### お気に入り

ログインユーザーは書籍をお気に入り登録できます。

- お気に入り登録・解除
- お気に入り一覧表示

登録・解除はトグル形式で処理しています。

---

### ランキング

レビュー評価をもとに書籍ランキングを表示します。

```text
GET /ranking
```

ランキングページはログインしていないユーザーも閲覧できます。

---

## 読書計画

ユーザーごとに書籍の読書計画を管理できます。

```text
GET    /reading-plans
GET    /reading-plans/create
POST   /reading-plans/create
GET    /reading-plans/{plan}/edit
PUT    /reading-plans/{plan}/update
POST   /reading-plans/{plan}/complete
DELETE /reading-plans/{plan}
```

主な管理情報：

- 書籍
- 読了予定日
- 読書状態
- 読書開始日時
- 読了日時

読書状態にはEnumを使用しています。

```text
reading   : 読書中
completed : 読了
```

---

## リマインダー通知

読書計画の期限に応じて、ユーザーへリマインダー通知を送信します。

LaravelのDatabase Notificationを使用しています。

実装クラス：

```text
app/Notifications/ReadingPlanReminderNotification.php
```

リマインダー処理はArtisan Commandとして実装しています。

```text
app/Console/Commands/SendReadingPlanReminders.php
```

コマンド：

```bash
php artisan reading-plans:send-reminders
```

Laravel Sailを使用する場合：

```bash
./vendor/bin/sail artisan reading-plans:send-reminders
```

通知一覧：

```text
GET /notifications
```

通知を既読にする：

```text
POST /notifications/{id}/read
```

---

## 読書レポート

ログインユーザー自身のレビュー・読書データを集計して表示します。

```text
GET /reports
```

主な集計内容：

- レビュー数
- 読了冊数
- 平均評価
- 評価分布
- 高評価書籍
- ジャンル別評価

---

# REST API

Laravel Sanctumを利用してAPI認証を実装しています。

APIのベースURL：

```text
/api/v1
```

---

## API認証

### ログイン

```http
POST /api/v1/login
```

ログイン成功時にSanctumのAPIトークンを発行します。

リクエスト例：

```json
{
    "email": "test@example.com",
    "password": "password"
}
```

認証が必要なAPIでは、取得したトークンをBearer Tokenとして送信します。

```http
Authorization: Bearer {token}
Accept: application/json
```

---

### ログアウト

```http
POST /api/v1/logout
```

認証が必要です。

現在利用しているSanctumトークンを削除します。

---

## 書籍API

| Method | Endpoint               | 内容         | 認証 |
| ------ | ---------------------- | ------------ | ---- |
| GET    | `/api/v1/books`        | 書籍一覧取得 | 不要 |
| GET    | `/api/v1/books/{book}` | 書籍詳細取得 | 不要 |
| POST   | `/api/v1/books`        | 書籍登録     | 必要 |
| PUT    | `/api/v1/books/{book}` | 書籍更新     | 必要 |
| DELETE | `/api/v1/books/{book}` | 書籍削除     | 必要 |

書籍更新・削除ではPolicyを利用し、対象書籍の作成者かどうかを確認します。

---

# 使用技術

## Backend

- PHP 8.1+
- Laravel 10
- Laravel Sanctum 3
- Laravel Fortify
- Eloquent ORM

## Frontend

- Blade
- Tailwind CSS 3
- Alpine.js
- Vite 5
- Axios

## Database

- MySQL 8.4

## Development

- Docker
- Laravel Sail
- phpMyAdmin
- PHPUnit
- Git
- GitHub
- Postman

---

# Docker構成

Laravel Sailを利用しています。

`compose.yaml`では以下のコンテナを使用しています。

| Service        | 内容          |
| -------------- | ------------- |
| `laravel.test` | Laravel / PHP |
| `mysql`        | MySQL 8.4     |
| `phpmyadmin`   | DB管理画面    |

Laravel：

```text
http://localhost
```

phpMyAdmin：

```text
http://localhost:8080
```

Vite：

```text
http://localhost:5173
```

---

## 環境構築

### 1. リポジトリをクローン

```bash
git clone https://github.com/Naruyama628/BookShelf.git
```

プロジェクトディレクトリへ移動します。

```bash
cd BookShelf
```

### 2. Laravel Sailのインストール

Composerの依存パッケージをDocker経由でインストールします。

```bash
docker run --rm \
-u "$(id -u):$(id -g)" \
-v "$(pwd):/var/www/html" \
-w /var/www/html \
-e COMPOSER_CACHE_DIR=/tmp/composer_cache \
laravelsail/php82-composer:latest \
composer install
```

### 3. `.env` ファイルの作成

```bash
cp .env.example .env
```

`.env` のデータベース設定を確認します。

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

GOOGLE_BOOKS_API_KEY=your_google_books_api_key
```

### 4. Laravel Sailの起動

```bash
./vendor/bin/sail up -d
```

### 5. Sailエイリアスの設定

WSL / Bashの場合：

```bash
echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.bashrc
source ~/.bashrc
```

以降は `sail` でコマンドを実行できます。

### 6. アプリケーションキーの生成

```bash
sail artisan key:generate
```

### 7. フロントエンドパッケージのインストール

```bash
sail npm install
```

### 8. データベースの構築

```bash
sail artisan migrate --seed
```

データベースを完全に作り直す場合：

```bash
sail artisan migrate:fresh --seed
```

### 9. Viteの起動

```bash
sail npm run dev
```

### 10. アプリケーションへアクセス

BookShelf：

```text
http://localhost
```

phpMyAdmin：

```text
http://localhost:8080
```

## 2回目以降の起動

```bash
sail up -d
sail npm run dev
```

終了：

```bash
sail down
```

## テスト手順

### 1. Sailを起動

```bash
sail up -d
```

### 2. テスト用データベースの準備

`.env.testing` のデータベース設定を確認します。

```env
APP_ENV=testing

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=testing
DB_USERNAME=sail
DB_PASSWORD=password
```

テスト用データベースが必要な構成の場合は、事前に作成してください。

### 3. 全テストを実行

```bash
sail artisan test
```

### 4. 特定のテストのみ実行

例：書籍APIのテスト

```bash
sail artisan test tests/Feature/BookApiTest.php
```

例：読書計画のテスト

```bash
sail artisan test tests/Feature/ReadingPlanTest.php
```

例：リマインダー通知のテスト

```bash
sail artisan test tests/Feature/ReadingPlanReminderTest.php
```

### 5. テスト名を指定して実行

```bash
sail artisan test --filter=テストメソッド名
```

例：

```bash
sail artisan test --filter=test_book_can_be_created
```

### 6. コードカバレッジを確認

```bash
sail artisan test --coverage
```

HTML形式のカバレッジレポートを出力する場合：

```bash
sail php vendor/bin/phpunit --coverage-html coverage
```

実行後、`coverage/` ディレクトリにレポートが生成されます。

### テストファイル

主なFeature Testは `tests/Feature/` に配置しています。

```text
tests/Feature/
├── AuthApiTest.php
├── BookApiTest.php
├── BookTest.php
├── FavoriteTest.php
├── GenreTest.php
├── RankingTest.php
├── ReadingPlanReminderTest.php
├── ReadingPlanTest.php
├── ReportTest.php
├── ReviewLikeTest.php
└── ReviewTest.php
```

# ER図

![ER図](ER.png)
