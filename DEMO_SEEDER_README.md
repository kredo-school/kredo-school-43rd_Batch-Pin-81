# Pin+81 Demo Seeder Patch

## 目的

`php artisan migrate:fresh --seed` だけで、画面確認に必要なダミーデータをまとめて登録できるようにするパッチです。

## 入っているファイル

```text
database/seeders/DatabaseSeeder.php
database/seeders/UserSeeder.php
database/seeders/FeatureSeeder.php
database/seeders/RestaurantSeeder.php
database/seeders/DemoSeeder.php
database/seeders/ReservationSeeder.php
app/Models/Reservation.php
app/Http/Controllers/Customer/BookingController.php
```

## 方針

- `reservation_code` カラムは追加しません。
- 予約番号は `RM001` のように `reservations.id` から表示します。
- 既存Seederは削除せず、最新DBに合わせて修正しています。
- `DemoSeeder` で、テーブル、メニュー、写真、レビュー、問い合わせ、通知などの画面確認用データを追加します。

## ログイン情報

パスワードは全アカウント共通で `password` です。

```text
Admin: admin@example.com
Customer: customer@example.com
Customer 2: customer2@example.com
Customer 3: customer3@example.com
Restaurant: restaurant@example.com
Restaurant 2: restaurant2@example.com
Pending Restaurant: pending-restaurant@example.com
Rejected Restaurant: rejected-restaurant@example.com
Suspended Restaurant: suspended-restaurant@example.com
```

## 実行方法

DBの中身を作り直してよい場合はこちらを推奨します。

```bash
php artisan migrate:fresh --seed
```

既存DBを消したくない場合は、以下でも実行できます。

```bash
php artisan migrate
php artisan db:seed
```

## 確認用データ

- approved / pending / rejected / suspended のレストラン
- 営業時間
- active / inactive のテーブル
- food / drink のメニュー
- 店舗写真、料理写真
- pending / confirmed / completed / cancelled の予約
- visible / hidden / reported review
- コメント、いいね、フォロー、お気に入り
- Customer問い合わせ、Restaurant問い合わせ、Admin返信、resolved問い合わせ
- Admin / Customer / Restaurant 通知
