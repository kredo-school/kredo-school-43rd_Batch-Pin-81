<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            ['feature_name' => 'English Menu Available'],
            ['feature_name' => 'Credit Cards Accepted'],
            ['feature_name' => 'Cash Only'],
            ['feature_name' => 'Reservations Required'],
            ['feature_name' => 'English Speaking Staff'],
            ['feature_name' => 'Vegetarian Options'],
            ['feature_name' => 'Halal Options'],
            ['feature_name' => 'Vegan Options'],
            ['feature_name' => 'Wi-Fi Available'],
            ['feature_name' => 'Private Room Available'],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(
                ['feature_name' => $feature['feature_name']],
                $feature
            );
        }
    }
}
