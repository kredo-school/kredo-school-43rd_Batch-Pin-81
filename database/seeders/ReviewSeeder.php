<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
  public function run(): void
  {
    DB::table('posts')->delete();

    $reviews = [
      [
        'id' => 1,
        'user_id' => 15,
        'restaurant_id' => 1,
        'rating' => 5,
        'description' => 'The sushi was incredibly fresh and the omakase course felt special from start to finish.',
        'image' => 'restaurant_photos/2onRbIYbelHr7bAwbFuvfASIreVy9UmmeMWi7urO.jpg',
        'created_at' => '2026-07-01 12:15:00',
      ],
      [
        'id' => 2,
        'user_id' => 16,
        'restaurant_id' => 2,
        'rating' => 4,
        'description' => 'Rich broth, great noodles, and a cozy atmosphere. I would definitely come back again.',
        'image' => 'restaurant_photos/8lRTQDqbR6QvvV2FBnok1ms3sxEJ9iuxgyKUX2uD.jpg',
        'created_at' => '2026-07-02 18:40:00',
      ],
      [
        'id' => 3,
        'user_id' => 17,
        'restaurant_id' => 3,
        'rating' => 5,
        'description' => 'Perfectly grilled yakitori with a great balance of smoke and seasoning.',
        'image' => 'restaurant_photos/Jm9c9ZMH1F4Co7LMOgLAhiwrQxVhJv148nhixUo3.jpg',
        'created_at' => '2026-07-03 19:05:00',
      ],
      [
        'id' => 4,
        'user_id' => 18,
        'restaurant_id' => 4,
        'rating' => 4,
        'description' => 'The tempura stayed crisp until the end and the seasonal vegetables were excellent.',
        'image' => 'restaurant_photos/UNfq1WsFtfKbIyQ38wyfgfycke0SnVFI06rwnufp.jpg',
        'created_at' => '2026-07-04 13:20:00',
      ],
      [
        'id' => 5,
        'user_id' => 19,
        'restaurant_id' => 5,
        'rating' => 5,
        'description' => 'Great wagyu quality and a polished service experience from the staff.',
        'image' => 'restaurant_photos/2onRbIYbelHr7bAwbFuvfASIreVy9UmmeMWi7urO.jpg',
        'created_at' => '2026-07-05 20:10:00',
      ],
      [
        'id' => 6,
        'user_id' => 15,
        'restaurant_id' => 6,
        'rating' => 4,
        'description' => 'Elegant presentation and a calm dining space. The kaiseki course felt thoughtfully prepared.',
        'image' => 'restaurant_photos/8lRTQDqbR6QvvV2FBnok1ms3sxEJ9iuxgyKUX2uD.jpg',
        'created_at' => '2026-07-06 11:30:00',
      ],
      [
        'id' => 7,
        'user_id' => 16,
        'restaurant_id' => 7,
        'rating' => 3,
        'description' => 'Good comfort food and a friendly vibe, but the wait time was a little long.',
        'image' => 'restaurant_photos/Jm9c9ZMH1F4Co7LMOgLAhiwrQxVhJv148nhixUo3.jpg',
        'created_at' => '2026-07-06 19:25:00',
      ],
      [
        'id' => 8,
        'user_id' => 17,
        'restaurant_id' => 8,
        'rating' => 5,
        'description' => 'Fresh seafood, generous portions, and a memorable lunch overall.',
        'image' => 'restaurant_photos/UNfq1WsFtfKbIyQ38wyfgfycke0SnVFI06rwnufp.jpg',
        'created_at' => '2026-07-07 12:50:00',
      ],
      [
        'id' => 9,
        'user_id' => 18,
        'restaurant_id' => 9,
        'rating' => 4,
        'description' => 'The miso dishes were comforting and full of flavor without being too heavy.',
        'image' => 'restaurant_photos/2onRbIYbelHr7bAwbFuvfASIreVy9UmmeMWi7urO.jpg',
        'created_at' => '2026-07-08 18:00:00',
      ],
      [
        'id' => 10,
        'user_id' => 19,
        'restaurant_id' => 10,
        'rating' => 5,
        'description' => 'The steak was tender and cooked exactly as requested. Excellent value for the quality.',
        'image' => 'restaurant_photos/8lRTQDqbR6QvvV2FBnok1ms3sxEJ9iuxgyKUX2uD.jpg',
        'created_at' => '2026-07-08 20:15:00',
      ],
      [
        'id' => 11,
        'user_id' => 15,
        'restaurant_id' => 11,
        'rating' => 4,
        'description' => 'Very satisfying lunch with attentive service and a relaxed atmosphere.',
        'image' => 'restaurant_photos/Jm9c9ZMH1F4Co7LMOgLAhiwrQxVhJv148nhixUo3.jpg',
        'created_at' => '2026-07-09 12:35:00',
      ],
      [
        'id' => 12,
        'user_id' => 16,
        'restaurant_id' => 12,
        'rating' => 5,
        'description' => 'A polished dinner with excellent pacing, bold flavors, and a clean interior.',
        'image' => 'restaurant_photos/UNfq1WsFtfKbIyQ38wyfgfycke0SnVFI06rwnufp.jpg',
        'created_at' => '2026-07-10 19:45:00',
      ],
      [
        'id' => 13,
        'user_id' => 17,
        'restaurant_id' => 13,
        'rating' => 4,
        'description' => 'Nice atmosphere for a casual meal and the dishes arrived quickly.',
        'image' => 'restaurant_photos/2onRbIYbelHr7bAwbFuvfASIreVy9UmmeMWi7urO.jpg',
        'created_at' => '2026-07-11 18:10:00',
      ],
      [
        'id' => 14,
        'user_id' => 18,
        'restaurant_id' => 14,
        'rating' => 5,
        'description' => 'Sweet, refreshing, and well presented. A great place for dessert and coffee.',
        'image' => 'restaurant_photos/8lRTQDqbR6QvvV2FBnok1ms3sxEJ9iuxgyKUX2uD.jpg',
        'created_at' => '2026-07-11 21:05:00',
      ],
    ];

    foreach ($reviews as $review) {
      DB::table('posts')->insert([
        'id' => $review['id'],
        'user_id' => $review['user_id'],
        'restaurant_id' => $review['restaurant_id'],
        'rating' => $review['rating'],
        'description' => $review['description'],
        'image' => $review['image'],
        'created_at' => $review['created_at'],
        'updated_at' => $review['created_at'],
        'deleted_at' => null,
      ]);
    }
  }
}
