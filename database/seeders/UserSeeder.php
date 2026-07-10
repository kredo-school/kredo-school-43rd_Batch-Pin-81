<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // データの重複を防ぐため、一度ユーザーテーブルをクリアします
        User::query()->delete();

        // --- 1. 元からあった14名の初期データ ---
        User::factory()->create([
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'customer@example.com',
            'avatar' => null,
            'role_id' => 2, // ログイン確認用および403エラー回避のため、Johnを管理者（role_id=2）に設定します
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 2,
            'first_name' => 'Masa',
            'last_name' => 'Tanaka',
            'email' => 'masa@example.com',
            'avatar' => null,
            'role_id' => 2,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 3,
            'first_name' => 'Yuki',
            'last_name' => 'Yamamoto',
            'email' => 'yuki@example.com',
            'avatar' => null,
            'role_id' => 2,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 4,
            'first_name' => 'Hiroshi',
            'last_name' => 'Suzuki',
            'email' => 'hiroshi@example.com',
            'avatar' => null,
            'role_id' => 2,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 5,
            'first_name' => 'Kenji',
            'last_name' => 'Sato',
            'email' => 'kenji@example.com',
            'avatar' => null,
            'role_id' => 2,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 6,
            'first_name' => 'Akira',
            'last_name' => 'Watanabe',
            'email' => 'akira@example.com',
            'avatar' => null,
            'role_id' => 2,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 7,
            'first_name' => 'Daichi',
            'last_name' => 'Nakamura',
            'email' => 'daichi@example.com',
            'avatar' => null,
            'role_id' => 1,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 8,
            'first_name' => 'Haruka',
            'last_name' => 'Ito',
            'email' => 'haruka@example.com',
            'avatar' => null,
            'role_id' => 1,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 9,
            'first_name' => 'Rina',
            'last_name' => 'Kobayashi',
            'email' => 'rina@example.com',
            'avatar' => null,
            'role_id' => 1,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 10,
            'first_name' => 'Takumi',
            'last_name' => 'Yoshida',
            'email' => 'takumi@example.com',
            'avatar' => null,
            'role_id' => 1,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 11,
            'first_name' => 'Naoki',
            'last_name' => 'Kato',
            'email' => 'naoki@example.com',
            'avatar' => null,
            'role_id' => 1,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 12,
            'first_name' => 'Emi',
            'last_name' => 'Mori',
            'email' => 'emi@example.com',
            'avatar' => null,
            'role_id' => 1,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 13,
            'first_name' => 'Shota',
            'last_name' => 'Abe',
            'email' => 'shota@example.com',
            'avatar' => null,
            'role_id' => 1,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'id' => 14,
            'first_name' => 'Yuna',
            'last_name' => 'Fujita',
            'email' => 'yuna@example.com',
            'avatar' => null,
            'role_id' => 1,
            'password' => Hash::make('password'),
        ]);

        // --- 2. 後から必要になった5名の追加データ (IDも固定化) ---
        $additionalUsers = [
            ['id' => 15, 'first_name' => 'Motoko', 'last_name' => 'kanazawa', 'username' => 'Pin+81', 'email' => 'motoko12345@gmail.com', 'role_id' => 1],
            ['id' => 16, 'first_name' => 'Sara', 'last_name' => 'Park', 'username' => 'sara', 'email' => 'sara@gmail.com', 'role_id' => 1],
            ['id' => 17, 'first_name' => 'Mina', 'last_name' => 'Kim', 'username' => 'mina37', 'email' => 'mina@gmail.com', 'role_id' => 1],
            ['id' => 18, 'first_name' => 'Mari', 'last_name' => 'Ono', 'username' => 'mari5', 'email' => 'mari@gmail.com', 'role_id' => 1],
            ['id' => 20, 'first_name' => 'Kana', 'last_name' => 'Park', 'username' => null, 'email' => 'kana@gmail.com', 'role_id' => 1],
        ];

        foreach ($additionalUsers as $user) {
            User::factory()->create([
                'id' => $user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role_id' => $user['role_id'],
                'password' => Hash::make('password'),
            ]);
        }
    }
}