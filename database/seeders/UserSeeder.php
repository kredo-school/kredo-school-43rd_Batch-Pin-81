<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->forceDelete();

        $users = [
            // Main test accounts
            ['id' => 1, 'first_name' => 'Admin', 'last_name' => 'User', 'username' => 'admin', 'email' => 'admin@example.com', 'role_id' => User::ROLE_ADMIN],
            ['id' => 2, 'first_name' => 'John', 'last_name' => 'Doe', 'username' => 'john_doe', 'email' => 'customer@example.com', 'role_id' => User::ROLE_USER],
            ['id' => 3, 'first_name' => 'Masa', 'last_name' => 'Tanaka', 'username' => 'sushi_masa_owner', 'email' => 'restaurant@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 4, 'first_name' => 'Yuki', 'last_name' => 'Yamamoto', 'username' => 'ramen_yuki_owner', 'email' => 'restaurant2@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 5, 'first_name' => 'Hiroshi', 'last_name' => 'Suzuki', 'username' => 'yakitori_hiroshi', 'email' => 'pending-restaurant@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 6, 'first_name' => 'Kenji', 'last_name' => 'Sato', 'username' => 'grill_kenji', 'email' => 'rejected-restaurant@example.com', 'role_id' => User::ROLE_RESTAURANT],
            ['id' => 7, 'first_name' => 'Akira', 'last_name' => 'Watanabe', 'username' => 'cafe_akira', 'email' => 'suspended-restaurant@example.com', 'role_id' => User::ROLE_RESTAURANT],

            // Customers for reservations/reviews/social features
            ['id' => 8, 'first_name' => 'Haruka', 'last_name' => 'Ito', 'username' => 'haruka', 'email' => 'customer2@example.com', 'role_id' => User::ROLE_USER],
            ['id' => 9, 'first_name' => 'Rina', 'last_name' => 'Kobayashi', 'username' => 'rina_k', 'email' => 'customer3@example.com', 'role_id' => User::ROLE_USER],
            ['id' => 10, 'first_name' => 'Takumi', 'last_name' => 'Yoshida', 'username' => 'takumi', 'email' => 'takumi@example.com', 'role_id' => User::ROLE_USER],
            ['id' => 11, 'first_name' => 'Naoki', 'last_name' => 'Kato', 'username' => 'naoki', 'email' => 'naoki@example.com', 'role_id' => User::ROLE_USER],
            ['id' => 12, 'first_name' => 'Emi', 'last_name' => 'Mori', 'username' => 'emi', 'email' => 'emi@example.com', 'role_id' => User::ROLE_USER],
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
