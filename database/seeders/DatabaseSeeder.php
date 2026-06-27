<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
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
        $restaurantOwner1 = User::factory()->create([
            'first_name' => 'Masa',
            'last_name' => 'Tanaka',
            'email' => 'masa@example.com',
            'avatar' => null,
            'role_id' => 2,
        ]);

        $restaurantOwner2 = User::factory()->create([
            'first_name' => 'Yuki',
            'last_name' => 'Yamamoto',
            'email' => 'yuki@example.com',
            'avatar' => null,
            'role_id' => 2,
        ]);

        $restaurantOwner3 = User::factory()->create([
            'first_name' => 'Hiroshi',
            'last_name' => 'Suzuki',
            'email' => 'hiroshi@example.com',
            'avatar' => null,
            'role_id' => 2,
        ]);

        $restaurantOwner4 = User::factory()->create([
            'first_name' => 'Kenji',
            'last_name' => 'Sato',
            'email' => 'kenji@example.com',
            'avatar' => null,
            'role_id' => 2,
        ]);

        $restaurantOwner5 = User::factory()->create([
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

        // Create restaurants
        DB::table('restaurants')->insert([
            [
                'user_id' => $restaurantOwner1->id,
                'restaurant_name' => 'Sushi Masaru',
                'description' => 'Premium omakase and sushi experience in the heart of Ginza. Fresh fish imported daily from Tsukiji market.',
                'address' => '1-2-3 Ginza, Chuo-ku, Tokyo',
                'phone_number' => 9012345678,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner2->id,
                'restaurant_name' => 'Ramen Ichiban',
                'description' => 'Authentic tonkotsu ramen with 20-hour bone broth. A favorite among locals and tourists alike.',
                'address' => '4-5-6 Shibuya, Shibuya-ku, Tokyo',
                'phone_number' => 9087654321,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner3->id,
                'restaurant_name' => 'Yakitori Tori',
                'description' => 'Traditional yakitori grilled over charcoal. Perfect spot for after-work gatherings and casual dining.',
                'address' => '7-8-9 Shinjuku, Shinjuku-ku, Tokyo',
                'phone_number' => 8876543210,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner1->id,
                'restaurant_name' => 'Tempura Kondo',
                'description' => 'Artisanal tempura using seasonal vegetables and premium seafood. Experience the crispy perfection.',
                'address' => '10-11-12 Roppongi, Minato-ku, Tokyo',
                'phone_number' => 7765432109,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner4->id,
                'restaurant_name' => 'Osaka Grill House',
                'description' => 'Modern Japanese barbecue featuring premium wagyu and seasonal ingredients.',
                'address' => '2-3-4 Umeda, Osaka',
                'phone_number' => '9011111111',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner2->id,
                'restaurant_name' => 'Kyoto Kaiseki Garden',
                'description' => 'Elegant kaiseki dining inspired by Kyoto traditions.',
                'address' => '5-6-7 Gion, Kyoto',
                'phone_number' => '9022222222',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner3->id,
                'restaurant_name' => 'Naniwa Okonomiyaki',
                'description' => 'Authentic Osaka-style okonomiyaki cooked fresh on iron griddles.',
                'address' => '8-9-10 Namba, Osaka',
                'phone_number' => '9033333333',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner3->id,
                'restaurant_name' => 'Hokkaido Seafood Market',
                'description' => 'Fresh crab, scallops and uni delivered directly from Hokkaido.',
                'address' => '11-12-13 Sapporo, Hokkaido',
                'phone_number' => '9044444444',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner5->id,
                'restaurant_name' => 'Nagoya Miso Kitchen',
                'description' => 'Specializing in miso katsu and local Nagoya favorites.',
                'address' => '14-15-16 Nagoya, Aichi',
                'phone_number' => '9055555555',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner4->id,
                'restaurant_name' => 'Kobe Steak Lounge',
                'description' => 'Premium Kobe beef prepared by expert chefs.',
                'address' => '17-18-19 Kobe, Hyogo',
                'phone_number' => '9066666666',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner4->id,
                'restaurant_name' => 'Tohoku Izakaya',
                'description' => 'Traditional izakaya serving regional dishes and local sake.',
                'address' => '20-21-22 Sendai, Miyagi',
                'phone_number' => '9077777777',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner4->id,
                'restaurant_name' => 'Shabu Shabu Sakura',
                'description' => 'All-you-can-eat shabu shabu with fresh vegetables and premium meats.',
                'address' => '23-24-25 Yokohama, Kanagawa',
                'phone_number' => '9088888888',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner5->id,
                'restaurant_name' => 'Udon Master',
                'description' => 'Handmade Sanuki udon served in traditional and modern styles.',
                'address' => '26-27-28 Takamatsu, Kagawa',
                'phone_number' => '9099999999',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner5->id,
                'restaurant_name' => 'Sakura Dessert Cafe',
                'description' => 'Japanese sweets, matcha desserts and specialty coffee.',
                'address' => '29-30-31 Asakusa, Tokyo',
                'phone_number' => '9010101010',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
