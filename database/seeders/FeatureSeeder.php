<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('feature_restaurant')->delete();
        Feature::query()->forceDelete();

        $features = [
            'English Menu Available',
            'English Speaking Staff',
            'Credit Cards Accepted',
            'Cash Only',
            'Wi-Fi Available',
            'Private Room Available',
            'Reservations Required',
            'Walk-ins Welcome',
            'Vegetarian Options',
            'Vegan Options',
            'Gluten-conscious Options',
            'Wheelchair Accessible',
            'Halal-friendly Options',
            'Child-friendly',
        ];

        foreach ($features as $featureName) {
            Feature::create([
                'feature_name' => $featureName,
            ]);
        }
    }
}
