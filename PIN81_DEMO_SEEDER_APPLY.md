# Pin81 プレゼン用共通Seeder更新

このZIPは、プロジェクトルートに上書きする差分ファイルです。

## 反映方法

1. 現在のブランチを確認

```bash
git branch --show-current
git status
```

`feature/demo-seeder-data` かつ作業ツリーがクリーンな状態で進めます。

2. ZIPをプロジェクトルートへ展開

```bash
unzip -o ~/Downloads/pin81-demo-seeder-update.zip -d .
```

ダウンロード先がDesktopなら、パスを `~/Desktop/...` に変更してください。

3. PHP構文確認

```bash
php -l database/seeders/FeatureSeeder.php
php -l database/seeders/DatabaseSeeder.php
php -l database/seeders/UserSeeder.php
php -l database/seeders/RestaurantSeeder.php
php -l database/seeders/DemoSeeder.php
php -l database/seeders/ReservationSeeder.php
php -l app/Http/Controllers/Restaurant/RestaurantController.php
php -l app/Http/Controllers/Customer/CategoryController.php
php -l app/Http/Controllers/Customer/AreaController.php
php -l app/Http/Controllers/Customer/RestaurantSearchController.php
```

4. DBと共通データを作り直す

```bash
php artisan optimize:clear
php artisan storage:link
php artisan migrate:fresh --seed
```

`public/storage` がすでに存在する場合、`storage:link` の警告は通常問題ありません。

## 共通ログイン

全アカウントのパスワードは `password` です。

- Admin: `admin@example.com`
- Customer: `customer@example.com`
- Sushi Masaru: `restaurant@example.com`
- Ramen Ichiban: `restaurant2@example.com`
- Kaiseki Kiyomi: `restaurant20@example.com`

追加店舗は `restaurant16@example.com` ～ `restaurant21@example.com` です。

## データ概要

- Restaurant: 21店舗
- Approved: 18店舗
- Sushi: 3店舗
- Ramen: 3店舗
- Pending / Rejected / Suspended: 各1店舗
- 実画像は `database/seeders/assets/demo` でGit共有
- 予約日は2026-07-16～2026-07-20
- 2026-07-18にオンライン予約、手動予約、walk-inを配置
- `customer@example.com` にUpcomingとPast Visitsを配置

## ReviewSeeder.phpについて

既存の `ReviewSeeder.php` は `DatabaseSeeder.php` から呼び出していません。
レビューは `DemoSeeder.php` に一本化しているため、今回は削除しなくても動作に影響しません。

## 動作確認

```bash
php artisan serve
```

- `Sushi` 検索で3店舗
- `Ramen` 検索で3店舗
- `Ginza` / `Shinjuku` / `Roppongi` / `Asakusa` のエリア検索
- `/my_reservations` の2026-07-18前後の予約
- Restaurant Dashboardの2026-07-18
- Restaurant詳細のMenu / Photos / Reviews
