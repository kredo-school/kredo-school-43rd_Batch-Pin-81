<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

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
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(
                ['feature_name' => $feature['feature_name']],
                $feature
            );
        }
    }
}