<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
  public function run(): void
  {
    Feature::query()->forceDelete();

    $features = [
      'English Menu Available',
      'Credit Cards Accepted',
      'English Speaking Staff',
      'Private Room Available',
      'Cash Only',
      'Wi-Fi Available',
      'Reservations Required',
      'Vegetarian Options',
      'Vegan Options',
    ];

    foreach ($features as $featureName) {
      Feature::create([
        'feature_name' => $featureName,
      ]);
    }
  }
}
