<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->forceDelete();

        $users = [
            ['id' => 1, 'first_name' => 'Admin', 'last_name' => 'User', 'username' => 'admin', 'email' => 'admin@example.com', 'role_id' => User::ROLE_ADMIN],
            ['id' => 2, 'first_name' => 'John', 'last_name' => 'Doe', 'username' => 'john_doe', 'email' => 'customer@example.com', 'role_id' => User::ROLE_USER],
            ['id' => 3, 'first_name' => 'Masa', 'last_name' => 'Tanaka', 'username' => 'sushi_masa_owner', 'email' => 'restaurant@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 4, 'first_name' => 'Yuki', 'last_name' => 'Yamamoto', 'username' => 'ramen_yuki_owner', 'email' => 'restaurant2@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 5, 'first_name' => 'Hiroshi', 'last_name' => 'Suzuki', 'username' => 'yakitori_hiroshi', 'email' => 'pending-restaurant@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 6, 'first_name' => 'Kenji', 'last_name' => 'Sato', 'username' => 'grill_kenji', 'email' => 'rejected-restaurant@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 7, 'first_name' => 'Akira', 'last_name' => 'Watanabe', 'username' => 'cafe_akira', 'email' => 'suspended-restaurant@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 8, 'first_name' => 'Haruka', 'last_name' => 'Ito', 'username' => 'haruka', 'email' => 'customer2@example.com', 'role_id' => User::ROLE_USER],
            ['id' => 9, 'first_name' => 'Rina', 'last_name' => 'Kobayashi', 'username' => 'rina_k', 'email' => 'customer3@example.com', 'role_id' => User::ROLE_USER],
            ['id' => 10, 'first_name' => 'Takumi', 'last_name' => 'Yoshida', 'username' => 'takumi', 'email' => 'takumi@example.com', 'role_id' => User::ROLE_USER],
            ['id' => 11, 'first_name' => 'Naoki', 'last_name' => 'Kato', 'username' => 'naoki', 'email' => 'naoki@example.com', 'role_id' => User::ROLE_USER],
            ['id' => 12, 'first_name' => 'Emi', 'last_name' => 'Mori', 'username' => 'emi', 'email' => 'emi@example.com', 'role_id' => User::ROLE_USER],

            // Additional Tokyo restaurant owners (restaurant IDs 6-15)
            ['id' => 13, 'first_name' => 'Aoi', 'last_name' => 'Nakamura', 'username' => 'tempura_aoi_owner', 'email' => 'restaurant6@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 14, 'first_name' => 'Daichi', 'last_name' => 'Hayashi', 'username' => 'udon_daichi_owner', 'email' => 'restaurant7@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 15, 'first_name' => 'Mei', 'last_name' => 'Shimizu', 'username' => 'vegan_mei_owner', 'email' => 'restaurant8@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 16, 'first_name' => 'Ren', 'last_name' => 'Kimura', 'username' => 'izakaya_ren_owner', 'email' => 'restaurant9@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 17, 'first_name' => 'Sora', 'last_name' => 'Inoue', 'username' => 'curry_sora_owner', 'email' => 'restaurant10@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 18, 'first_name' => 'Yuna', 'last_name' => 'Matsumoto', 'username' => 'italian_yuna_owner', 'email' => 'restaurant11@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 19, 'first_name' => 'Kaito', 'last_name' => 'Abe', 'username' => 'bbq_kaito_owner', 'email' => 'restaurant12@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 20, 'first_name' => 'Mio', 'last_name' => 'Ikeda', 'username' => 'thai_mio_owner', 'email' => 'restaurant13@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 21, 'first_name' => 'Riku', 'last_name' => 'Hashimoto', 'username' => 'bistro_riku_owner', 'email' => 'restaurant14@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 22, 'first_name' => 'Hina', 'last_name' => 'Yamada', 'username' => 'bakery_hina_owner', 'email' => 'restaurant15@example.com', 'role_id' => User::ROLE_RESTAURANT],
        ];

        foreach ($users as $user) {
            User::create([
                'id' => $user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'avatar' => null,
                'role_id' => $user['role_id'],
                'is_active' => true,
                'password' => Hash::make('password'),
            ]);
        }
    }
}
