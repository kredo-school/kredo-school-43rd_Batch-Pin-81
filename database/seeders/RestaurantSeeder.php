<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $restaurantOwner1 = User::where('email', 'masa@example.com')->first();
        $restaurantOwner2 = User::where('email', 'yuki@example.com')->first();
        $restaurantOwner3 = User::where('email', 'hiroshi@example.com')->first();
        $restaurantOwner4 = User::where('email', 'kenji@example.com')->first();
        $restaurantOwner5 = User::where('email', 'akira@example.com')->first();


        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];

        $hoursRegular = [];

        foreach ($days as $day) {

            // 20% chance restaurant is closed
            $isClosed = rand(1, 100) <= 20;

            if ($isClosed) {
                $hoursRegular[$day] = [
                    'closed' => true,
                ];
            } else {
                $hoursRegular[$day] = [
                    0 => [
                        'open'  => sprintf('%02d:00', rand(10, 12)),
                        'close' => sprintf('%02d:00', rand(20, 23)),
                    ],
                    'closed' => false,
                ];
            }
        }

        $hoursSplitShift = [];

        foreach ($days as $day) {

            // 15% chance restaurant is closed
            $isClosed = rand(1, 100) <= 15;

            if ($isClosed) {
                $hoursSplitShift[$day] = [
                    'closed' => true,
                ];
            } else {
                $hoursSplitShift[$day] = [
                    0 => [
                        'open'  => '11:00',
                        'close' => '14:00',
                    ],
                    1 => [
                        'open'  => '17:00',
                        'close' => '22:00',
                    ],
                    'closed' => false,
                ];
            }
        }

        $schedules = [
            $hoursRegular,
            $hoursSplitShift,
        ];

        // Create restaurants
        DB::table('restaurants')->insert([

            [
                'user_id' => $restaurantOwner1->id,
                'restaurant_name' => 'Sushi Masaru',
                'description' => 'Premium omakase and sushi experience in the heart of Ginza. Fresh fish imported daily from Tsukiji market.',
                'postal_code' => '104-0061',
                'prefecture' => 'Tokyo',
                'city' => 'Chuo-ku',
                'street_address_building' => '1-2-3 Ginza',
                'phone_number' => 9012345678,
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner2->id,
                'restaurant_name' => 'Ramen Ichiban',
                'description' => 'Authentic tonkotsu ramen with 20-hour bone broth. A favorite among locals and tourists alike.',
                'postal_code' => '150-0002',
                'prefecture' => 'Tokyo',
                'city' => 'Shibuya-ku',
                'street_address_building' => '4-5-6 Shibuya',
                'phone_number' => 9087654321,
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner3->id,
                'restaurant_name' => 'Yakitori Tori',
                'description' => 'Traditional yakitori grilled over charcoal. Perfect spot for after-work gatherings and casual dining.',
                'postal_code' => '160-0022',
                'prefecture' => 'Tokyo',
                'city' => 'Shinjuku-ku',
                'street_address_building' => '7-8-9 Shinjuku',
                'phone_number' => 8876543210,
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner1->id,
                'restaurant_name' => 'Tempura Kondo',
                'description' => 'Artisanal tempura using seasonal vegetables and premium seafood. Experience the crispy perfection.',
                'postal_code' => '106-0032',
                'prefecture' => 'Tokyo',
                'city' => 'Minato-ku',
                'street_address_building' => '10-11-12 Roppongi',
                'phone_number' => 7765432109,
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner4->id,
                'restaurant_name' => 'Osaka Grill House',
                'description' => 'Modern Japanese barbecue featuring premium wagyu and seasonal ingredients.',
                'postal_code' => '530-0001',
                'prefecture' => 'Osaka',
                'city' => 'Kita-ku',
                'street_address_building' => '2-3-4 Umeda',
                'phone_number' => '9011111111',
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner2->id,
                'restaurant_name' => 'Kyoto Kaiseki Garden',
                'description' => 'Elegant kaiseki dining inspired by Kyoto traditions.',
                'postal_code' => '111-0032',
                'prefecture' => 'Tokyo',
                'city' => 'Taito-ku',
                'street_address_building' => '29-30-31 Asakusa',
                'phone_number' => '9022222222',
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner3->id,
                'restaurant_name' => 'Naniwa Okonomiyaki',
                'description' => 'Authentic Osaka-style okonomiyaki cooked fresh on iron griddles.',
                'postal_code' => '605-0073',
                'prefecture' => 'Kyoto',
                'city' => 'Higashiyama-ku',
                'street_address_building' => '5-6-7 Gion',
                'phone_number' => '9033333333',
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner3->id,
                'restaurant_name' => 'Hokkaido Seafood Market',
                'description' => 'Fresh crab, scallops and uni delivered directly from Hokkaido.',
                'postal_code' => '542-0076',
                'prefecture' => 'Osaka',
                'city' => 'Chuo-ku',
                'street_address_building' => '8-9-10 Namba',
                'phone_number' => '9044444444',
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner5->id,
                'restaurant_name' => 'Nagoya Miso Kitchen',
                'description' => 'Specializing in miso katsu and local Nagoya favorites.',
                'postal_code' => '060-0001',
                'prefecture' => 'Hokkaido',
                'city' => 'Sapporo',
                'street_address_building' => '11-12-13 Kita 1 Jo',
                'phone_number' => '9055555555',
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner4->id,
                'restaurant_name' => 'Kobe Steak Lounge',
                'description' => 'Premium Kobe beef prepared by expert chefs.',
                'postal_code' => '450-0002',
                'prefecture' => 'Aichi',
                'city' => 'Nagoya',
                'street_address_building' => '14-15-16 Meieki',
                'phone_number' => '9066666666',
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner4->id,
                'restaurant_name' => 'Tohoku Izakaya',
                'description' => 'Traditional izakaya serving regional dishes and local sake.',
                'postal_code' => '650-0001',
                'prefecture' => 'Hyogo',
                'city' => 'Kobe',
                'street_address_building' => '17-18-19 Sannomiya',
                'phone_number' => '9077777777',
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner4->id,
                'restaurant_name' => 'Shabu Shabu Sakura',
                'description' => 'All-you-can-eat shabu shabu with fresh vegetables and premium meats.',
                'postal_code' => '980-0014',
                'prefecture' => 'Miyagi',
                'city' => 'Sendai',
                'street_address_building' => '20-21-22 Honcho',
                'phone_number' => '9088888888',
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner5->id,
                'restaurant_name' => 'Udon Master',
                'description' => 'Handmade Sanuki udon served in traditional and modern styles.',
                'postal_code' => '220-0005',
                'prefecture' => 'Kanagawa',
                'city' => 'Yokohama',
                'street_address_building' => '23-24-25 Minamisaiwai',
                'phone_number' => '9099999999',
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner5->id,
                'restaurant_name' => 'Sakura Dessert Cafe',
                'description' => 'Japanese sweets, matcha desserts and specialty coffee.',
                'postal_code' => '760-0023',
                'prefecture' => 'Kagawa',
                'city' => 'Takamatsu',
                'street_address_building' => '26-27-28 Kotobukicho',
                'phone_number' => '9010101010',
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
