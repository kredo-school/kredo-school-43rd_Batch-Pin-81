<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Feature;
use App\Models\Menu;
use App\Models\Photo;
use App\Models\Post;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedDemoImages();
        $this->seedCategories();
        $this->seedRestaurantRelations();
        $this->seedTables();
        $this->seedMenusAndPhotos();
        $this->seedReviewsAndSocialData();
        $this->seedContacts();
        $this->seedNotifications();
    }


    private function seedDemoImages(): void
    {
        $imagePaths = [
            'demo/photos/sushi-omakase.jpg',
            'demo/photos/sashimi.jpg',
            'demo/photos/sushi-interior.jpg',
            'demo/photos/sushi-exterior.jpg',
            'demo/photos/ramen.jpg',
            'demo/photos/gyoza.jpg',
            'demo/photos/ramen-interior.jpg',
            'demo/menus/omakase-sushi.jpg',
            'demo/menus/sashimi.jpg',
            'demo/menus/green-tea.jpg',
            'demo/menus/tonkotsu-ramen.jpg',
            'demo/menus/gyoza.jpg',
            'demo/menus/oolong.jpg',
            'demo/reviews/sushi-review.jpg',
            'demo/reviews/private-room.jpg',
            'demo/reviews/ramen-review.jpg',
            'demo/reviews/reported-review.jpg',
            'demo/reviews/hidden-review.jpg',
        ];

        // Small valid JPEG placeholder. The goal is to make seeded image paths displayable
        // even when no real uploaded files exist yet.
        $placeholderJpeg = base64_decode(
            '/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsL' .
            'DBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/' .
            '2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy' .
            'MjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIAAhEBAxEB' .
            '/8QAFQABAQAAAAAAAAAAAAAAAAAAAAX/xAAUEAEAAAAAAAAAAAAAAAAAAAAA' .
            '/9oADAMBAAIQAxAAAAH/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oACAEBAAEF' .
            'An//xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oACAEDAQE/AX//xAAUEQEAAAAA' .
            'AAAAAAAAAAAA/9oACAECAQE/AX//xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oA' .
            'CAEBAAY/An//xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oACAEBAAE/IX//2gAM' .
            'AwEAAgADAAAAEP/EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQMBAT8QH//' .
            'EABQRAQAAAAAAAAAAAAAAAAAAABD/2gAIAQIBAT8QH//EABQQAQAAAAAAAAAA' .
            'AAAAAAAAABD/2gAIAQEAAT8QH//Z'
        );

        foreach ($imagePaths as $path) {
            Storage::disk('public')->put($path, $placeholderJpeg);
        }
    }

    private function seedCategories(): void
    {
        $categories = [
            'Sushi',
            'Ramen',
            'Yakitori',
            'Japanese BBQ',
            'Cafe',
            'Vegetarian Friendly',
        ];

        foreach ($categories as $categoryName) {
            Category::updateOrCreate(
                ['category_name' => $categoryName],
                ['category_name' => $categoryName]
            );
        }
    }

    private function seedRestaurantRelations(): void
    {
        $categoryIds = Category::pluck('id', 'category_name');
        $featureIds = Feature::pluck('id', 'feature_name');

        $relations = [
            1 => [
                'categories' => ['Sushi', 'Vegetarian Friendly'],
                'features' => ['English Menu Available', 'Credit Cards Accepted', 'English Speaking Staff', 'Private Room Available'],
            ],
            2 => [
                'categories' => ['Ramen'],
                'features' => ['English Menu Available', 'Cash Only', 'Wi-Fi Available'],
            ],
            3 => [
                'categories' => ['Yakitori'],
                'features' => ['Reservations Required', 'Credit Cards Accepted'],
            ],
            4 => [
                'categories' => ['Japanese BBQ'],
                'features' => ['Credit Cards Accepted'],
            ],
            5 => [
                'categories' => ['Cafe', 'Vegetarian Friendly'],
                'features' => ['Vegetarian Options', 'Vegan Options', 'Wi-Fi Available'],
            ],
        ];

        foreach ($relations as $restaurantId => $relation) {
            $restaurant = Restaurant::find($restaurantId);
            if (!$restaurant) {
                continue;
            }

            $restaurant->categories()->sync(
                collect($relation['categories'])
                    ->map(fn ($name) => $categoryIds[$name] ?? null)
                    ->filter()
                    ->values()
                    ->all()
            );

            $restaurant->features()->sync(
                collect($relation['features'])
                    ->map(fn ($name) => $featureIds[$name] ?? null)
                    ->filter()
                    ->values()
                    ->all()
            );
        }
    }

    private function seedTables(): void
    {
        DB::table('tables')->delete();

        $tables = [
            ['id' => 1, 'restaurant_id' => 1, 'table_name' => 'Counter 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 2, 'restaurant_id' => 1, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 3, 'restaurant_id' => 1, 'table_name' => 'Private Room', 'capacity' => 6, 'is_active' => true],
            ['id' => 4, 'restaurant_id' => 1, 'table_name' => 'Old Patio', 'capacity' => 4, 'is_active' => false],
            ['id' => 5, 'restaurant_id' => 2, 'table_name' => 'Ramen Counter 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 6, 'restaurant_id' => 2, 'table_name' => 'Ramen Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 7, 'restaurant_id' => 2, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 8, 'restaurant_id' => 3, 'table_name' => 'Pending Table A', 'capacity' => 4, 'is_active' => true],
        ];

        foreach ($tables as $table) {
            DB::table('tables')->insert(array_merge($table, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    private function seedMenusAndPhotos(): void
    {
        DB::table('photos')->delete();
        DB::table('menus')->delete();

        $menus = [
            ['id' => 1, 'restaurant_id' => 1, 'menu_name' => 'Omakase Sushi Set', 'price' => 8800, 'menu_category' => 'food', 'description' => 'Chef selected sushi course.', 'image_path' => 'demo/menus/omakase-sushi.jpg', 'menu_image' => 'demo/menus/omakase-sushi.jpg'],
            ['id' => 2, 'restaurant_id' => 1, 'menu_name' => 'Seasonal Sashimi', 'price' => 4200, 'menu_category' => 'food', 'description' => 'Fresh seasonal sashimi plate.', 'image_path' => 'demo/menus/sashimi.jpg', 'menu_image' => 'demo/menus/sashimi.jpg'],
            ['id' => 3, 'restaurant_id' => 1, 'menu_name' => 'Green Tea', 'price' => 500, 'menu_category' => 'drink', 'description' => 'Hot Japanese green tea.', 'image_path' => 'demo/menus/green-tea.jpg', 'menu_image' => 'demo/menus/green-tea.jpg'],
            ['id' => 4, 'restaurant_id' => 2, 'menu_name' => 'Tonkotsu Ramen', 'price' => 1200, 'menu_category' => 'food', 'description' => 'Rich pork bone broth ramen.', 'image_path' => 'demo/menus/tonkotsu-ramen.jpg', 'menu_image' => 'demo/menus/tonkotsu-ramen.jpg'],
            ['id' => 5, 'restaurant_id' => 2, 'menu_name' => 'Gyoza', 'price' => 650, 'menu_category' => 'food', 'description' => 'Pan-fried dumplings.', 'image_path' => 'demo/menus/gyoza.jpg', 'menu_image' => 'demo/menus/gyoza.jpg'],
            ['id' => 6, 'restaurant_id' => 2, 'menu_name' => 'Iced Oolong Tea', 'price' => 450, 'menu_category' => 'drink', 'description' => 'Cold oolong tea.', 'image_path' => 'demo/menus/oolong.jpg', 'menu_image' => 'demo/menus/oolong.jpg'],
        ];

        foreach ($menus as $menu) {
            DB::table('menus')->insert(array_merge($menu, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $photos = [
            ['restaurant_id' => 1, 'menu_id' => 1, 'photo_path' => 'demo/photos/sushi-omakase.jpg', 'photo_category' => 'food'],
            ['restaurant_id' => 1, 'menu_id' => 2, 'photo_path' => 'demo/photos/sashimi.jpg', 'photo_category' => 'food'],
            ['restaurant_id' => 1, 'menu_id' => null, 'photo_path' => 'demo/photos/sushi-interior.jpg', 'photo_category' => 'interior'],
            ['restaurant_id' => 1, 'menu_id' => null, 'photo_path' => 'demo/photos/sushi-exterior.jpg', 'photo_category' => 'exterior'],
            ['restaurant_id' => 2, 'menu_id' => 4, 'photo_path' => 'demo/photos/ramen.jpg', 'photo_category' => 'food'],
            ['restaurant_id' => 2, 'menu_id' => 5, 'photo_path' => 'demo/photos/gyoza.jpg', 'photo_category' => 'food'],
            ['restaurant_id' => 2, 'menu_id' => null, 'photo_path' => 'demo/photos/ramen-interior.jpg', 'photo_category' => 'interior'],
        ];

        foreach ($photos as $photo) {
            Photo::create($photo);
        }
    }

    private function seedReviewsAndSocialData(): void
    {
        DB::table('likes')->delete();
        DB::table('comments')->delete();
        DB::table('follows')->delete();
        DB::table('favorites')->delete();
        Post::query()->forceDelete();

        $posts = [
            ['id' => 1, 'user_id' => 2, 'restaurant_id' => 1, 'rating' => 5, 'description' => 'Amazing sushi and very friendly English-speaking staff.', 'image' => 'demo/reviews/sushi-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 2, 'user_id' => 8, 'restaurant_id' => 1, 'rating' => 4, 'description' => 'Great omakase experience. The private room was comfortable.', 'image' => 'demo/reviews/private-room.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 3, 'user_id' => 9, 'restaurant_id' => 2, 'rating' => 5, 'description' => 'The ramen was rich and delicious. Easy to order in English.', 'image' => 'demo/reviews/ramen-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 4, 'user_id' => 10, 'restaurant_id' => 2, 'rating' => 2, 'description' => 'Reported review sample for admin review moderation.', 'image' => 'demo/reviews/reported-review.jpg', 'status' => 'visible', 'is_reported' => true],
            ['id' => 5, 'user_id' => 11, 'restaurant_id' => 1, 'rating' => 1, 'description' => 'Hidden review sample.', 'image' => 'demo/reviews/hidden-review.jpg', 'status' => 'hidden', 'is_reported' => true],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }

        DB::table('comments')->insert([
            ['id' => 1, 'post_id' => 1, 'user_id' => 8, 'body' => 'I want to try this restaurant too!', 'is_reported' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'post_id' => 3, 'user_id' => 2, 'body' => 'This ramen looks great.', 'is_reported' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'post_id' => 4, 'user_id' => 9, 'body' => 'Reported comment sample.', 'is_reported' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('likes')->insert([
            ['user_id' => 8, 'post_id' => 1],
            ['user_id' => 9, 'post_id' => 1],
            ['user_id' => 2, 'post_id' => 3],
        ]);

        DB::table('follows')->insert([
            ['follower_id' => 2, 'following_id' => 8],
            ['follower_id' => 8, 'following_id' => 2],
            ['follower_id' => 9, 'following_id' => 2],
        ]);

        DB::table('favorites')->insert([
            ['user_id' => 2, 'restaurant_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 2, 'restaurant_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 8, 'restaurant_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    private function seedContacts(): void
    {
        Contact::query()->delete();

        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $customer = User::where('email', 'customer@example.com')->firstOrFail();
        $customer2 = User::where('email', 'customer2@example.com')->firstOrFail();
        $restaurantOwner = User::where('email', 'restaurant@example.com')->firstOrFail();
        $restaurant = Restaurant::findOrFail(1);

        $customerContact = Contact::create([
            'id' => 1,
            'user_id' => $customer->id,
            'restaurant_id' => null,
            'parent_id' => null,
            'title' => 'Question about reservation cancellation',
            'message' => 'I would like to know how to cancel my reservation from My Reservations page.',
            'attachments' => null,
            'status' => 'replied',
        ]);

        Contact::create([
            'id' => 2,
            'user_id' => $admin->id,
            'restaurant_id' => null,
            'parent_id' => $customerContact->id,
            'title' => null,
            'message' => 'You can cancel it from My Reservations. Please open the reservation detail and press Cancel.',
            'attachments' => null,
            'status' => 'replied',
        ]);

        Contact::create([
            'id' => 3,
            'user_id' => $customer->id,
            'restaurant_id' => null,
            'parent_id' => $customerContact->id,
            'title' => null,
            'message' => 'Thank you. I found the button.',
            'attachments' => null,
            'status' => 'open',
        ]);

        $restaurantContact = Contact::create([
            'id' => 4,
            'user_id' => $restaurantOwner->id,
            'restaurant_id' => $restaurant->id,
            'parent_id' => null,
            'title' => 'Need help updating business hours',
            'message' => 'We want to add split business hours for lunch and dinner.',
            'attachments' => null,
            'status' => 'open',
        ]);

        Contact::create([
            'id' => 5,
            'user_id' => $admin->id,
            'restaurant_id' => $restaurant->id,
            'parent_id' => $restaurantContact->id,
            'title' => null,
            'message' => 'Please go to Restaurant Profile and update the Operating Hours section.',
            'attachments' => null,
            'status' => 'replied',
        ]);

        Contact::create([
            'id' => 6,
            'user_id' => $customer2->id,
            'restaurant_id' => null,
            'parent_id' => null,
            'title' => 'Resolved sample inquiry',
            'message' => 'This is a resolved contact thread sample.',
            'attachments' => null,
            'status' => 'resolved',
        ]);
    }

    private function seedNotifications(): void
    {
        DB::table('notifications')->delete();

        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $customer = User::where('email', 'customer@example.com')->firstOrFail();
        $restaurantOwner = User::where('email', 'restaurant@example.com')->firstOrFail();

        DB::table('notifications')->insert([
            [
                'id' => (string) Str::uuid(),
                'type' => 'demo.restaurant.application',
                'notifiable_type' => User::class,
                'notifiable_id' => $admin->id,
                'data' => json_encode([
                    'title' => 'New Restaurant Application',
                    'message' => 'Yakitori Tori is waiting for approval.',
                    'url' => route('admin.restaurants.pending'),
                    'button_text' => 'Review Application',
                ]),
                'read_at' => null,
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ],
            [
                'id' => (string) Str::uuid(),
                'type' => 'demo.reservation.submitted',
                'notifiable_type' => User::class,
                'notifiable_id' => $restaurantOwner->id,
                'data' => json_encode([
                    'title' => 'New Reservation Request',
                    'message' => 'John Doe requested a reservation at Sushi Masaru.',
                    'reservation_code' => 'RM001',
                    'url' => route('restaurant.reservations'),
                    'button_text' => 'View Reservations',
                ]),
                'read_at' => null,
                'created_at' => now()->subHour(),
                'updated_at' => now()->subHour(),
            ],
            [
                'id' => (string) Str::uuid(),
                'type' => 'demo.contact.reply',
                'notifiable_type' => User::class,
                'notifiable_id' => $customer->id,
                'data' => json_encode([
                    'title' => 'Admin replied to your inquiry',
                    'message' => 'Please check the contact page for details.',
                    'url' => route('customer.contact.index'),
                    'button_text' => 'Open Contact',
                ]),
                'read_at' => now()->subMinutes(20),
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ]);
    }
}
