<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'restaurant_name' => fake()->company(),
      'description' => fake()->paragraph(),
      'address' => fake()->address(),
      'phone_number' => (int) fake()->numerify('#########'),
    ];
  }
}
