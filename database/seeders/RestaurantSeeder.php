<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Restaurant::query()->forceDelete();

        $operatingHoursRegular = $this->operatingHours([
            'Monday' => [['open' => '11:00', 'close' => '22:00']],
            'Tuesday' => [['open' => '11:00', 'close' => '22:00']],
            'Wednesday' => [['open' => '11:00', 'close' => '22:00']],
            'Thursday' => [['open' => '11:00', 'close' => '22:00']],
            'Friday' => [['open' => '11:00', 'close' => '23:00']],
            'Saturday' => [['open' => '10:00', 'close' => '23:00']],
            'Sunday' => [['open' => '10:00', 'close' => '21:00']],
        ]);

        $operatingHoursSplit = $this->operatingHours([
            'Monday' => [['open' => '11:30', 'close' => '14:30'], ['open' => '17:00', 'close' => '22:00']],
            'Tuesday' => [['open' => '11:30', 'close' => '14:30'], ['open' => '17:00', 'close' => '22:00']],
            'Wednesday' => [],
            'Thursday' => [['open' => '11:30', 'close' => '14:30'], ['open' => '17:00', 'close' => '22:00']],
            'Friday' => [['open' => '11:30', 'close' => '14:30'], ['open' => '17:00', 'close' => '23:00']],
            'Saturday' => [['open' => '12:00', 'close' => '15:00'], ['open' => '17:00', 'close' => '23:00']],
            'Sunday' => [['open' => '12:00', 'close' => '21:00']],
        ]);

        $restaurants = [
            [
                'id' => 1,
                'owner_email' => 'restaurant@example.com',
                'restaurant_name' => 'Sushi Masaru',
                'description' => 'Premium omakase and sushi experience in the heart of Ginza. Fresh fish is prepared daily for international guests.',
                'postal_code' => '1040061',
                'prefecture' => 'Tokyo',
                'city' => 'Chuo City',
                'street_address_building' => 'Ginza 4-2-8, 3F',
                'phone_number' => '0312345678',
                'website' => 'https://example.com/sushi-masaru',
                'instagram' => 'sushi_masaru',
                'facebook' => 'sushimasaru',
                'twitter' => 'sushi_masaru',
                'capacity' => 28,
                'stay_duration' => 120,
                'operating_hours' => $operatingHoursRegular,
                'business_license' => 'business_licenses/sample-license.pdf',
                'latitude' => 35.671901,
                'longitude' => 139.765000,
                'status' => Restaurant::STATUS_APPROVED,
            ],
            [
                'id' => 2,
                'owner_email' => 'restaurant2@example.com',
                'restaurant_name' => 'Ramen Ichiban',
                'description' => 'Authentic tonkotsu ramen with rich broth and English-friendly service.',
                'postal_code' => '1500043',
                'prefecture' => 'Tokyo',
                'city' => 'Shibuya City',
                'street_address_building' => 'Dogenzaka 1-2-3, 1F',
                'phone_number' => '0398765432',
                'website' => 'https://example.com/ramen-ichiban',
                'instagram' => 'ramen_ichiban',
                'facebook' => 'ramenichiban',
                'twitter' => 'ramen_ichiban',
                'capacity' => 36,
                'stay_duration' => 90,
                'operating_hours' => $operatingHoursSplit,
                'business_license' => 'business_licenses/sample-license.pdf',
                'latitude' => 35.658034,
                'longitude' => 139.701636,
                'status' => Restaurant::STATUS_APPROVED,
            ],
            [
                'id' => 3,
                'owner_email' => 'pending-restaurant@example.com',
                'restaurant_name' => 'Yakitori Tori',
                'description' => 'Charcoal-grilled yakitori restaurant waiting for admin approval.',
                'postal_code' => '1600022',
                'prefecture' => 'Tokyo',
                'city' => 'Shinjuku City',
                'street_address_building' => 'Shinjuku 3-17-21, 2F',
                'phone_number' => '0387654321',
                'website' => null,
                'instagram' => 'yakitori_tori',
                'facebook' => null,
                'twitter' => null,
                'capacity' => 24,
                'stay_duration' => 120,
                'operating_hours' => $operatingHoursRegular,
                'business_license' => 'business_licenses/sample-license.pdf',
                'latitude' => 35.690921,
                'longitude' => 139.700258,
                'status' => Restaurant::STATUS_PENDING,
            ],
            [
                'id' => 4,
                'owner_email' => 'rejected-restaurant@example.com',
                'restaurant_name' => 'Osaka Grill House',
                'description' => 'Rejected sample restaurant for admin status testing.',
                'postal_code' => '5300001',
                'prefecture' => 'Osaka',
                'city' => 'Kita Ward',
                'street_address_building' => 'Umeda 3-2-123, 4F',
                'phone_number' => '0611111111',
                'website' => null,
                'instagram' => null,
                'facebook' => null,
                'twitter' => null,
                'capacity' => 40,
                'stay_duration' => 120,
                'operating_hours' => $operatingHoursRegular,
                'business_license' => 'business_licenses/sample-license.pdf',
                'latitude' => 34.702485,
                'longitude' => 135.495951,
                'status' => Restaurant::STATUS_REJECTED,
            ],
            [
                'id' => 5,
                'owner_email' => 'suspended-restaurant@example.com',
                'restaurant_name' => 'Sakura Dessert Cafe',
                'description' => 'Suspended sample restaurant for admin status testing.',
                'postal_code' => '7600031',
                'prefecture' => 'Kagawa',
                'city' => 'Takamatsu',
                'street_address_building' => 'Kitahamacho 15-11',
                'phone_number' => '0871010101',
                'website' => 'https://example.com/sakura-dessert-cafe',
                'instagram' => 'sakura_dessert_cafe',
                'facebook' => null,
                'twitter' => null,
                'capacity' => 18,
                'stay_duration' => 90,
                'operating_hours' => $operatingHoursSplit,
                'business_license' => 'business_licenses/sample-license.pdf',
                'latitude' => 34.342787,
                'longitude' => 134.046574,
                'status' => Restaurant::STATUS_SUSPENDED,
            ],
        ];

        foreach ($restaurants as $restaurant) {
            $owner = User::where('email', $restaurant['owner_email'])->firstOrFail();
            unset($restaurant['owner_email']);

            $restaurant['user_id'] = $owner->id;
            $restaurant['created_at'] = now();
            $restaurant['updated_at'] = now();

            Restaurant::withoutEvents(function () use ($restaurant) {
                Restaurant::create($restaurant);
            });
        }
    }

    private function operatingHours(array $days): array
    {
        $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $hours = [];

        foreach ($weekDays as $day) {
            $shifts = $days[$day] ?? [];
            $hours[$day] = ['closed' => empty($shifts)];

            foreach ($shifts as $index => $shift) {
                $hours[$day][$index] = $shift;
            }
        }

        return $hours;
    }
}
