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
            'introduction' => 'Food enthusiast and regular restaurant visitor',
        ]);

        // Create restaurant owner users and their restaurants
        $restaurantOwner1 = User::factory()->create([
            'first_name' => 'Masa',
            'last_name' => 'Tanaka',
            'email' => 'masa@example.com',
            'avatar' => null,
            'role_id' => 2,
            'introduction' => 'Head Chef at Sushi Masaru',
        ]);

        $restaurantOwner2 = User::factory()->create([
            'first_name' => 'Yuki',
            'last_name' => 'Yamamoto',
            'email' => 'yuki@example.com',
            'avatar' => null,
            'role_id' => 2,
            'introduction' => 'Owner of Ramen Ichiban',
        ]);

        $restaurantOwner3 = User::factory()->create([
            'first_name' => 'Hiroshi',
            'last_name' => 'Suzuki',
            'email' => 'hiroshi@example.com',
            'avatar' => null,
            'role_id' => 2,
            'introduction' => 'Traditional yakitori master',
        ]);

        // Create restaurants
        DB::table('restaurants')->insert([
            [
                'user_id' => $restaurantOwner1->id,
                'restaurant_name' => 'Sushi Masaru',
                'profile_image' => null,
                'image' => null,
                'description' => 'Premium omakase and sushi experience in the heart of Ginza. Fresh fish imported daily from Tsukiji market.',
                'address' => '1-2-3 Ginza, Chuo-ku, Tokyo',
                'email' => 'info@sushi-masaru.jp',
                'phone_number' => 9012345678,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner2->id,
                'restaurant_name' => 'Ramen Ichiban',
                'profile_image' => null,
                'image' => null,
                'description' => 'Authentic tonkotsu ramen with 20-hour bone broth. A favorite among locals and tourists alike.',
                'address' => '4-5-6 Shibuya, Shibuya-ku, Tokyo',
                'email' => 'contact@ramen-ichiban.jp',
                'phone_number' => 9087654321,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner3->id,
                'restaurant_name' => 'Yakitori Tori',
                'profile_image' => null,
                'image' => null,
                'description' => 'Traditional yakitori grilled over charcoal. Perfect spot for after-work gatherings and casual dining.',
                'address' => '7-8-9 Shinjuku, Shinjuku-ku, Tokyo',
                'email' => 'booking@yakitori-tori.jp',
                'phone_number' => 8876543210,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner1->id,
                'restaurant_name' => 'Tempura Kondo',
                'profile_image' => null,
                'image' => null,
                'description' => 'Artisanal tempura using seasonal vegetables and premium seafood. Experience the crispy perfection.',
                'address' => '10-11-12 Roppongi, Minato-ku, Tokyo',
                'email' => 'reserve@tempura-kondo.jp',
                'phone_number' => 7765432109,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
