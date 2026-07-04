<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            ['features_name' => 'English Menu Available'],
            ['features_name' => 'Credit Cards Accepted'],
            ['features_name' => 'Cash Only'],
            ['features_name' => 'Reservations Required'],
            ['features_name' => 'English Speaking Staff'],
            ['features_name' => 'Vegetarian Options'],
            ['features_name' => 'Halal Options'],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(
                ['features_name' => $feature['features_name']],
                $feature
            );
        }
    }
}