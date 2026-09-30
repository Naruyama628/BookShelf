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

| Method | Endpoint | 内容 | 認証 |
|---|---|---|---|
| GET | `/api/v1/books` | 書籍一覧取得 | 不要 |
| GET | `/api/v1/books/{book}` | 書籍詳細取得 | 不要 |
| POST | `/api/v1/books` | 書籍登録 | 必要 |
| PUT | `/api/v1/books/{book}` | 書籍更新 | 必要 |
| DELETE | `/api/v1/books/{book}` | 書籍削除 | 必要 |

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

| Service | 内容 |
|---|---|
| `laravel.test` | Laravel / PHP |
| `mysql` | MySQL 8.4 |
| `phpmyadmin` | DB管理画面 |

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

# 環境構築

## 1. リポジトリをクローン

```bash
git clone https://github.com/Naruyama628/BookShelf.git
```

```bash
cd BookShelf
```

---

## 2. Composerパッケージをインストール

```bash
composer install
```

---

## 3. 環境変数ファイルを作成

```bash
cp .env.example .env
```

---

## 4. Dockerコンテナを起動

```bash
./vendor/bin/sail up -d
```

---

## 5. APP_KEYを生成

```bash
./vendor/bin/sail artisan key:generate
```

---

## 6. データベースを作成

```bash
./vendor/bin/sail artisan migrate
```

Seederも実行する場合：

```bash
./vendor/bin/sail artisan db:seed
```

データベースを作り直してSeederまで実行する場合：

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

> `migrate:fresh` は既存テーブルをすべて削除するため、保存されているデータも削除されます。

---

## 7. フロントエンドパッケージをインストール

```bash
npm install
```

---

## 8. Viteを起動

```bash
npm run dev
```

---

# テスト

PHPUnitによるFeature Testを実装しています。

現在の主なテスト：

```text
AuthApiTest.php
BookApiTest.php
BookTest.php
FavoriteTest.php
GenreTest.php
RankingTest.php
ReadingPlanReminderTest.php
ReadingPlanTest.php
ReportTest.php
ReviewLikeTest.php
ReviewTest.php
```

テスト実行：

```bash
./vendor/bin/sail artisan test
```

PHPUnitを直接実行：

```bash
./vendor/bin/sail

# ER図
![ER図](ER.png)