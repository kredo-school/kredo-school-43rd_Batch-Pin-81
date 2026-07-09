<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create customer user
        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'customer@example.com',
            'avatar' => null,
            'role_id' => 1,
        ]);

        // Create restaurant owner users and their restaurants
        User::factory()->create([
            'first_name' => 'Masa',
            'last_name' => 'Tanaka',
            'email' => 'masa@example.com',
            'avatar' => null,
            'role_id' => 2,
        ]);

        User::factory()->create([
            'first_name' => 'Yuki',
            'last_name' => 'Yamamoto',
            'email' => 'yuki@example.com',
            'avatar' => null,
            'role_id' => 2,
        ]);

        User::factory()->create([
            'first_name' => 'Hiroshi',
            'last_name' => 'Suzuki',
            'email' => 'hiroshi@example.com',
            'avatar' => null,
            'role_id' => 2,
        ]);

        User::factory()->create([
            'first_name' => 'Kenji',
            'last_name' => 'Sato',
            'email' => 'kenji@example.com',
            'avatar' => null,
            'role_id' => 2,
        ]);

        User::factory()->create([
            'first_name' => 'Akira',
            'last_name' => 'Watanabe',
            'email' => 'akira@example.com',
            'avatar' => null,
            'role_id' => 2,
        ]);

        User::factory()->create([
            'first_name' => 'Daichi',
            'last_name' => 'Nakamura',
            'email' => 'daichi@example.com',
            'avatar' => null,
            'role_id' => 1,
        ]);

        User::factory()->create([
            'first_name' => 'Haruka',
            'last_name' => 'Ito',
            'email' => 'haruka@example.com',
            'avatar' => null,
            'role_id' => 1,
        ]);

        User::factory()->create([
            'first_name' => 'Rina',
            'last_name' => 'Kobayashi',
            'email' => 'rina@example.com',
            'avatar' => null,
            'role_id' => 1,
        ]);

        User::factory()->create([
            'first_name' => 'Takumi',
            'last_name' => 'Yoshida',
            'email' => 'takumi@example.com',
            'avatar' => null,
            'role_id' => 1,
        ]);

        User::factory()->create([
            'first_name' => 'Naoki',
            'last_name' => 'Kato',
            'email' => 'naoki@example.com',
            'avatar' => null,
            'role_id' => 1,
        ]);

        User::factory()->create([
            'first_name' => 'Emi',
            'last_name' => 'Mori',
            'email' => 'emi@example.com',
            'avatar' => null,
            'role_id' => 1,
        ]);

        User::factory()->create([
            'first_name' => 'Shota',
            'last_name' => 'Abe',
            'email' => 'shota@example.com',
            'avatar' => null,
            'role_id' => 1,
        ]);

        User::factory()->create([
            'first_name' => 'Yuna',
            'last_name' => 'Fujita',
            'email' => 'yuna@example.com',
            'avatar' => null,
            'role_id' => 1,
        ]);
    }
}
