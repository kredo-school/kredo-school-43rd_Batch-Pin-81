<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'postal_code' => '105-0011',
                'prefecture' => 'Tokyo',
                'city' => 'Minato City',
                'street_address_building' => '4 Chome-2-8 Shibakoen',
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
                'postal_code' => '150-0043',
                'prefecture' => 'Tokyo',
                'city' => 'Shibuya',
                'street_address_building' => '1F Shibuya Fukuras 1 Chome-2-3 Dogenzaka',
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
                'city' => 'Shinjuku City',
                'street_address_building' => '2F Kawamoto Bldg. 3 Chome-17-21 Shinjuku',
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
                'postal_code' => '105-0003',
                'prefecture' => 'Tokyo',
                'city' => 'Minato City',
                'street_address_building' => '2F FACE Toranomon 2 Chome-15-6 Nishishinbashi',
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
                'city' => 'Kita Ward',
                'street_address_building' => '4F Osaka Inogate 3 Chome-2-123 Umeda',
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
                'city' => 'Taito City',
                'street_address_building' => '1F Mizukami Bldg. 4 Chome-11-9 Asakusa',
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
                'postal_code' => '605-0084',
                'prefecture' => 'Kyoto',
                'city' => 'Higashiyama Ward',
                'street_address_building' => '376-6 Kiyomotocho',
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
                'city' => 'Chuo ward',
                'street_address_building' => '1 Chome-5-2 Namba',
                'phone_number' => '9044444444',
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $restaurantOwner5->id,
                'restaurant_name' => 'Hokkaido Miso Kitchen',
                'description' => 'Specializing in miso katsu and local Nagoya favorites.',
                'postal_code' => '060-0063',
                'prefecture' => 'Hokkaido',
                'city' => 'Sapporo',
                'street_address_building' => '2F 7 Chome-7-26 Minami 3 Jonishi Chuo Ward',
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
                'postal_code' => '650-0022',
                'prefecture' => 'Hyogo',
                'city' => 'Kobe',
                'street_address_building' => '1F 1 Chome-1-1 Motomachidori Chuo Ward',
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
                'postal_code' => '650-0011',
                'prefecture' => 'Hyogo',
                'city' => 'Kobe',
                'street_address_building' => 'B1F IT Bldg. 2 Chome-16-2 Shimoyamatedori Chuo Ward',
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
                'postal_code' => '984-0004',
                'prefecture' => 'Miyagi',
                'city' => 'Sendai',
                'street_address_building' => '1-5 Rokuchonome Higashimachi Wakabayashi Ward',
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
                'postal_code' => '231-0013',
                'prefecture' => 'Kanagawa',
                'city' => 'Yokohama',
                'street_address_building' => '6 Chome-74 Sumiyoshicho Naka Ward',
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
                'postal_code' => '760-0031',
                'prefecture' => 'Kagawa',
                'city' => 'Takamatsu',
                'street_address_building' => '15-11 Kitahamacho',
                'phone_number' => '9010101010',
                'operating_hours' => json_encode($schedules[array_rand($schedules)]),
                'business_license' => 'licenses/sample-license.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
