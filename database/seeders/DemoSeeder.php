<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Feature;
use App\Models\Photo;
use App\Models\Post;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedDemoImages();
        $this->seedCategories();
        $this->seedRestaurantRelations();
        $this->seedTables();
        $this->seedMenusAndPhotos();
        $this->seedReviewsAndSocialData();
        $this->seedContacts();
        // $this->seedNotifications();
    }

    private function seedDemoImages(): void
    {
        $sourceDirectory = database_path('seeders/assets/demo');

        if (!File::isDirectory($sourceDirectory)) {
            throw new RuntimeException(
                'Demo image directory not found: ' . $sourceDirectory
            );
        }

        Storage::disk('public')->deleteDirectory('demo');

        foreach (File::allFiles($sourceDirectory) as $file) {
            $relativePath = str_replace(
                DIRECTORY_SEPARATOR,
                '/',
                $file->getRelativePathname()
            );

            Storage::disk('public')->put(
                'demo/' . $relativePath,
                File::get($file->getPathname())
            );
        }
    }

    private function seedCategories(): void
    {
        $categories = [
            'Sushi',
            'Ramen',
            'Yakitori',
            'Kaiseki',
            'Japanese BBQ',
            'Cafe',
            'Vegetarian Friendly',
            'Tempura',
            'Udon',
            'Vegan',
            'Izakaya',
            'Curry',
            'Italian',
            'Thai',
            'French',
            'Bakery',
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
        DB::table('category_restaurant')->delete();
        DB::table('feature_restaurant')->delete();

        $categoryIds = Category::pluck('id', 'category_name');
        $featureIds = Feature::pluck('id', 'feature_name');

        $relations = [
            1 => [
                'categories' => ['Sushi', 'Vegetarian Friendly'],
                'features' => ['English Menu Available', 'Credit Cards Accepted', 'English Speaking Staff', 'Private Room Available', 'Reservations Required'],
            ],
            2 => [
                'categories' => ['Ramen'],
                'features' => ['English Menu Available', 'Cash Only', 'Wi-Fi Available', 'Walk-ins Welcome'],
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
            6 => [
                'categories' => ['Tempura'],
                'features' => ['English Menu Available', 'Credit Cards Accepted', 'Reservations Required'],
            ],
            7 => [
                'categories' => ['Udon'],
                'features' => ['English Menu Available', 'Cash Only', 'Wi-Fi Available', 'Walk-ins Welcome'],
            ],
            8 => [
                'categories' => ['Vegan', 'Vegetarian Friendly'],
                'features' => ['English Menu Available', 'Vegetarian Options', 'Vegan Options', 'Credit Cards Accepted', 'Gluten-conscious Options'],
            ],
            9 => [
                'categories' => ['Izakaya'],
                'features' => ['English Menu Available', 'Credit Cards Accepted', 'Private Room Available', 'Walk-ins Welcome'],
            ],
            10 => [
                'categories' => ['Curry'],
                'features' => ['English Menu Available', 'Vegetarian Options', 'Credit Cards Accepted'],
            ],
            11 => [
                'categories' => ['Italian'],
                'features' => ['English Menu Available', 'Credit Cards Accepted', 'Reservations Required'],
            ],
            12 => [
                'categories' => ['Japanese BBQ'],
                'features' => ['English Menu Available', 'Credit Cards Accepted', 'Private Room Available'],
            ],
            13 => [
                'categories' => ['Thai', 'Vegetarian Friendly'],
                'features' => ['English Menu Available', 'Vegetarian Options', 'Credit Cards Accepted'],
            ],
            14 => [
                'categories' => ['French'],
                'features' => ['English Menu Available', 'Credit Cards Accepted', 'Reservations Required'],
            ],
            15 => [
                'categories' => ['Bakery', 'Cafe'],
                'features' => ['English Menu Available', 'Vegetarian Options', 'Wi-Fi Available', 'Child-friendly'],
            ],
            16 => [
                'categories' => ['Sushi'],
                'features' => ['English Menu Available', 'English Speaking Staff', 'Credit Cards Accepted', 'Reservations Required'],
            ],
            17 => [
                'categories' => ['Sushi'],
                'features' => ['English Menu Available', 'Credit Cards Accepted', 'Walk-ins Welcome', 'Child-friendly'],
            ],
            18 => [
                'categories' => ['Ramen'],
                'features' => ['English Menu Available', 'Credit Cards Accepted', 'Wi-Fi Available', 'Walk-ins Welcome'],
            ],
            19 => [
                'categories' => ['Ramen', 'Vegetarian Friendly'],
                'features' => ['English Menu Available', 'Vegetarian Options', 'Credit Cards Accepted', 'Walk-ins Welcome'],
            ],
            20 => [
                'categories' => ['Kaiseki', 'Tempura'],
                'features' => ['English Menu Available', 'English Speaking Staff', 'Credit Cards Accepted', 'Private Room Available', 'Reservations Required'],
            ],
            21 => [
                'categories' => ['Yakitori', 'Izakaya'],
                'features' => ['English Menu Available', 'Credit Cards Accepted', 'Walk-ins Welcome', 'Private Room Available'],
            ],
        ];

        foreach ($relations as $restaurantId => $relation) {
            $restaurant = Restaurant::find($restaurantId);

            if (!$restaurant) {
                continue;
            }

            $restaurant->categories()->sync(
                collect($relation['categories'])
                    ->map(fn(string $name) => $categoryIds[$name] ?? null)
                    ->filter()
                    ->values()
                    ->all()
            );

            $restaurant->features()->sync(
                collect($relation['features'])
                    ->map(fn(string $name) => $featureIds[$name] ?? null)
                    ->filter()
                    ->values()
                    ->all()
            );
        }
    }

    private function seedTables(): void
    {
        DB::table('reservations')->delete();
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
            ['id' => 9, 'restaurant_id' => 4, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 10, 'restaurant_id' => 4, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 11, 'restaurant_id' => 4, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 12, 'restaurant_id' => 5, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 13, 'restaurant_id' => 5, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 14, 'restaurant_id' => 5, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 15, 'restaurant_id' => 6, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 16, 'restaurant_id' => 6, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 17, 'restaurant_id' => 6, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 18, 'restaurant_id' => 7, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 19, 'restaurant_id' => 7, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 20, 'restaurant_id' => 7, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 21, 'restaurant_id' => 8, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 22, 'restaurant_id' => 8, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 23, 'restaurant_id' => 8, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 24, 'restaurant_id' => 9, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 25, 'restaurant_id' => 9, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 26, 'restaurant_id' => 9, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 27, 'restaurant_id' => 10, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 28, 'restaurant_id' => 10, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 29, 'restaurant_id' => 10, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 30, 'restaurant_id' => 11, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 31, 'restaurant_id' => 11, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 32, 'restaurant_id' => 11, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 33, 'restaurant_id' => 12, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 34, 'restaurant_id' => 12, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 35, 'restaurant_id' => 12, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 36, 'restaurant_id' => 13, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 37, 'restaurant_id' => 13, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 38, 'restaurant_id' => 13, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 39, 'restaurant_id' => 14, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 40, 'restaurant_id' => 14, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 41, 'restaurant_id' => 14, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 42, 'restaurant_id' => 15, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 43, 'restaurant_id' => 15, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 44, 'restaurant_id' => 15, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 45, 'restaurant_id' => 16, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 46, 'restaurant_id' => 16, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 47, 'restaurant_id' => 16, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 48, 'restaurant_id' => 17, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 49, 'restaurant_id' => 17, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 50, 'restaurant_id' => 17, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 51, 'restaurant_id' => 18, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 52, 'restaurant_id' => 18, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 53, 'restaurant_id' => 18, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 54, 'restaurant_id' => 19, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 55, 'restaurant_id' => 19, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 56, 'restaurant_id' => 19, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 57, 'restaurant_id' => 20, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 58, 'restaurant_id' => 20, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 59, 'restaurant_id' => 20, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
            ['id' => 60, 'restaurant_id' => 21, 'table_name' => 'Counter / Table 1', 'capacity' => 2, 'is_active' => true],
            ['id' => 61, 'restaurant_id' => 21, 'table_name' => 'Table A', 'capacity' => 4, 'is_active' => true],
            ['id' => 62, 'restaurant_id' => 21, 'table_name' => 'Group Table', 'capacity' => 6, 'is_active' => true],
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

        $menuDefinitions = [
            1 => [
                ['menu_name' => 'Omakase Sushi Set', 'price' => 8800, 'menu_category' => 'food', 'description' => 'Chef-selected seasonal nigiri and small dishes.', 'image' => 'demo/menus/omakase-sushi.jpg'],
                ['menu_name' => 'Seasonal Sashimi', 'price' => 4200, 'menu_category' => 'food', 'description' => 'A selection of fresh seasonal fish.', 'image' => 'demo/menus/sushi-review.jpg'],
                ['menu_name' => 'Wagyu Nigiri', 'price' => 3200, 'menu_category' => 'food', 'description' => 'Seared wagyu over vinegared rice.', 'image' => 'demo/menus/wagyu-steak.jpg'],
                ['menu_name' => 'Matcha Dessert Plate', 'price' => 900, 'menu_category' => 'food', 'description' => 'Matcha cake and ice cream.', 'image' => 'demo/menus/matcha-dessert.jpg'],
                ['menu_name' => 'Premium Sake', 'price' => 1200, 'menu_category' => 'drink', 'description' => 'Chilled Japanese sake selected for sushi.', 'image' => 'demo/menus/craft-beer.jpg'],
                ['menu_name' => 'Green Tea', 'price' => 500, 'menu_category' => 'drink', 'description' => 'Hot Japanese green tea.', 'image' => 'demo/menus/matcha-dessert.jpg'],
            ],
            2 => [
                ['menu_name' => 'Tonkotsu Ramen', 'price' => 1200, 'menu_category' => 'food', 'description' => 'Rich pork-bone broth with chashu and egg.', 'image' => 'demo/menus/tonkotsu-ramen.jpg'],
                ['menu_name' => 'Spicy Miso Ramen', 'price' => 1350, 'menu_category' => 'food', 'description' => 'Miso broth with a balanced spicy finish.', 'image' => 'demo/menus/ramen-review.jpg'],
                ['menu_name' => 'Crispy Gyoza', 'price' => 650, 'menu_category' => 'food', 'description' => 'Pan-fried dumplings with house sauce.', 'image' => 'demo/menus/yakitori.jpg'],
                ['menu_name' => 'Matcha Ice Cream', 'price' => 600, 'menu_category' => 'food', 'description' => 'Creamy matcha ice cream.', 'image' => 'demo/menus/matcha-dessert.jpg'],
                ['menu_name' => 'Draft Beer', 'price' => 700, 'menu_category' => 'drink', 'description' => 'Cold Japanese draft beer.', 'image' => 'demo/menus/craft-beer.jpg'],
                ['menu_name' => 'Iced Oolong Tea', 'price' => 450, 'menu_category' => 'drink', 'description' => 'Refreshing cold oolong tea.', 'image' => 'demo/menus/pink-cocktail.jpg'],
            ],
            6 => [
                ['menu_name' => 'Seasonal Tempura Set', 'price' => 2400, 'menu_category' => 'food', 'description' => 'Shrimp and seasonal vegetables in a light batter.', 'image' => 'demo/menus/tempura-tendon.jpg'],
                ['menu_name' => 'Tempura Tendon', 'price' => 1800, 'menu_category' => 'food', 'description' => 'Tempura served over rice with house sauce.', 'image' => 'demo/menus/tempura-review.jpg'],
                ['menu_name' => 'Green Tea', 'price' => 450, 'menu_category' => 'drink', 'description' => 'Hot green tea.', 'image' => 'demo/menus/matcha-dessert.jpg'],
            ],
            7 => [
                ['menu_name' => 'Handmade Udon Bowl', 'price' => 1200, 'menu_category' => 'food', 'description' => 'Fresh thick noodles in a clear dashi broth.', 'image' => 'demo/menus/tonkotsu-ramen.jpg'],
                ['menu_name' => 'Steamed Dumplings', 'price' => 700, 'menu_category' => 'food', 'description' => 'Soft dumplings served with citrus soy.', 'image' => 'demo/menus/steamed-dumplings.jpg'],
                ['menu_name' => 'Iced Tea', 'price' => 400, 'menu_category' => 'drink', 'description' => 'Unsweetened iced tea.', 'image' => 'demo/menus/pink-cocktail.jpg'],
            ],
            8 => [
                ['menu_name' => 'Vegan Bento Plate', 'price' => 1800, 'menu_category' => 'food', 'description' => 'Seasonal vegetables, tofu, and rice.', 'image' => 'demo/menus/seafood-paella.jpg'],
                ['menu_name' => 'Matcha Soy Dessert', 'price' => 750, 'menu_category' => 'food', 'description' => 'Dairy-free matcha dessert.', 'image' => 'demo/menus/matcha-dessert.jpg'],
                ['menu_name' => 'Herbal Soda', 'price' => 550, 'menu_category' => 'drink', 'description' => 'Refreshing house-made herbal soda.', 'image' => 'demo/menus/pink-cocktail.jpg'],
            ],
            9 => [
                ['menu_name' => 'Izakaya Sharing Plate', 'price' => 2200, 'menu_category' => 'food', 'description' => 'A selection of grilled and fried small plates.', 'image' => 'demo/menus/yakitori.jpg'],
                ['menu_name' => 'Grilled Wagyu Bites', 'price' => 2600, 'menu_category' => 'food', 'description' => 'Tender grilled wagyu with vegetables.', 'image' => 'demo/menus/wagyu-steak.jpg'],
                ['menu_name' => 'Craft Beer', 'price' => 750, 'menu_category' => 'drink', 'description' => 'Local craft beer on tap.', 'image' => 'demo/menus/craft-beer.jpg'],
            ],
            10 => [
                ['menu_name' => 'Tokyo Spice Curry', 'price' => 1400, 'menu_category' => 'food', 'description' => 'Japanese curry with customizable spice level.', 'image' => 'demo/menus/seafood-paella.jpg'],
                ['menu_name' => 'Vegetable Curry', 'price' => 1300, 'menu_category' => 'food', 'description' => 'Vegetable-packed mild curry.', 'image' => 'demo/menus/seafood-paella.jpg'],
                ['menu_name' => 'Lassi', 'price' => 500, 'menu_category' => 'drink', 'description' => 'Creamy yogurt drink.', 'image' => 'demo/menus/pink-cocktail.jpg'],
            ],
            11 => [
                ['menu_name' => 'Margherita Pizza', 'price' => 1700, 'menu_category' => 'food', 'description' => 'Tomato, mozzarella, and basil.', 'image' => 'demo/menus/margherita-pizza.jpg'],
                ['menu_name' => 'Seasonal Pasta', 'price' => 1900, 'menu_category' => 'food', 'description' => 'Handmade pasta with seasonal ingredients.', 'image' => 'demo/menus/pasta.jpg'],
                ['menu_name' => 'Sparkling Wine', 'price' => 900, 'menu_category' => 'drink', 'description' => 'A glass of chilled sparkling wine.', 'image' => 'demo/menus/sparkling-wine.jpg'],
            ],
            12 => [
                ['menu_name' => 'Wagyu Yakiniku Set', 'price' => 4200, 'menu_category' => 'food', 'description' => 'Premium wagyu cuts for table-side grilling.', 'image' => 'demo/menus/wagyu-steak.jpg'],
                ['menu_name' => 'Grilled Vegetable Plate', 'price' => 1200, 'menu_category' => 'food', 'description' => 'Seasonal vegetables for the grill.', 'image' => 'demo/menus/seafood-paella.jpg'],
                ['menu_name' => 'Draft Beer', 'price' => 700, 'menu_category' => 'drink', 'description' => 'Cold draft beer.', 'image' => 'demo/menus/craft-beer.jpg'],
            ],
            13 => [
                ['menu_name' => 'Thai Green Curry', 'price' => 1500, 'menu_category' => 'food', 'description' => 'Aromatic coconut curry with vegetables.', 'image' => 'demo/menus/seafood-paella.jpg'],
                ['menu_name' => 'Seafood Stir-fry', 'price' => 1800, 'menu_category' => 'food', 'description' => 'Seafood and vegetables with Thai herbs.', 'image' => 'demo/menus/seafood-paella.jpg'],
                ['menu_name' => 'Pink Lime Soda', 'price' => 550, 'menu_category' => 'drink', 'description' => 'Sweet and tart lime soda.', 'image' => 'demo/menus/pink-cocktail.jpg'],
            ],
            14 => [
                ['menu_name' => 'Bistro Steak Plate', 'price' => 3200, 'menu_category' => 'food', 'description' => 'Grilled steak with seasonal vegetables.', 'image' => 'demo/menus/wagyu-steak.jpg'],
                ['menu_name' => 'Chef Pasta', 'price' => 1900, 'menu_category' => 'food', 'description' => 'Daily pasta from the chef.', 'image' => 'demo/menus/pasta.jpg'],
                ['menu_name' => 'Sparkling Wine', 'price' => 950, 'menu_category' => 'drink', 'description' => 'A glass of sparkling wine.', 'image' => 'demo/menus/sparkling-wine.jpg'],
            ],
            15 => [
                ['menu_name' => 'Bakery Sandwich Set', 'price' => 1100, 'menu_category' => 'food', 'description' => 'Fresh bread sandwich with salad.', 'image' => 'demo/menus/margherita-pizza.jpg'],
                ['menu_name' => 'Matcha Cake', 'price' => 650, 'menu_category' => 'food', 'description' => 'Soft matcha cake.', 'image' => 'demo/menus/matcha-dessert.jpg'],
                ['menu_name' => 'Coffee', 'price' => 450, 'menu_category' => 'drink', 'description' => 'Freshly brewed coffee.', 'image' => 'demo/menus/pink-cocktail.jpg'],
            ],
            16 => [
                ['menu_name' => 'Ginza Nigiri Set', 'price' => 5600, 'menu_category' => 'food', 'description' => 'Twelve pieces of seasonal nigiri.', 'image' => 'demo/menus/omakase-sushi.jpg'],
                ['menu_name' => 'Wagyu Sushi', 'price' => 2800, 'menu_category' => 'food', 'description' => 'Lightly seared wagyu sushi.', 'image' => 'demo/menus/wagyu-steak.jpg'],
                ['menu_name' => 'Japanese Sake', 'price' => 1000, 'menu_category' => 'drink', 'description' => 'Dry sake selected for sushi.', 'image' => 'demo/menus/craft-beer.jpg'],
            ],
            17 => [
                ['menu_name' => 'Asakusa Sushi Platter', 'price' => 3200, 'menu_category' => 'food', 'description' => 'Nigiri and rolls for an easy casual meal.', 'image' => 'demo/menus/sushi-review.jpg'],
                ['menu_name' => 'Matcha Dessert', 'price' => 700, 'menu_category' => 'food', 'description' => 'Matcha cake with ice cream.', 'image' => 'demo/menus/matcha-dessert.jpg'],
                ['menu_name' => 'Green Tea', 'price' => 400, 'menu_category' => 'drink', 'description' => 'Hot green tea.', 'image' => 'demo/menus/matcha-dessert.jpg'],
            ],
            18 => [
                ['menu_name' => 'Black Garlic Ramen', 'price' => 1350, 'menu_category' => 'food', 'description' => 'Rich ramen finished with black garlic oil.', 'image' => 'demo/menus/tonkotsu-ramen.jpg'],
                ['menu_name' => 'Crispy Gyoza', 'price' => 650, 'menu_category' => 'food', 'description' => 'Pan-fried dumplings.', 'image' => 'demo/menus/yakitori.jpg'],
                ['menu_name' => 'Draft Beer', 'price' => 650, 'menu_category' => 'drink', 'description' => 'Cold draft beer.', 'image' => 'demo/menus/craft-beer.jpg'],
            ],
            19 => [
                ['menu_name' => 'Chicken Paitan Ramen', 'price' => 1250, 'menu_category' => 'food', 'description' => 'Creamy chicken broth with chashu.', 'image' => 'demo/menus/ramen-review.jpg'],
                ['menu_name' => 'Vegetarian Ramen', 'price' => 1200, 'menu_category' => 'food', 'description' => 'Vegetable broth with mushrooms and greens.', 'image' => 'demo/menus/tonkotsu-ramen.jpg'],
                ['menu_name' => 'Iced Tea', 'price' => 400, 'menu_category' => 'drink', 'description' => 'Cold unsweetened tea.', 'image' => 'demo/menus/pink-cocktail.jpg'],
            ],
            20 => [
                ['menu_name' => 'Seasonal Kaiseki Course', 'price' => 9800, 'menu_category' => 'food', 'description' => 'A multi-course seasonal Japanese dinner.', 'image' => 'demo/menus/tempura-tendon.jpg'],
                ['menu_name' => 'Chef Sashimi Selection', 'price' => 3800, 'menu_category' => 'food', 'description' => 'Fresh sashimi selected by the chef.', 'image' => 'demo/menus/omakase-sushi.jpg'],
                ['menu_name' => 'Seasonal Tempura', 'price' => 2600, 'menu_category' => 'food', 'description' => 'Shrimp and vegetables in a crisp light batter.', 'image' => 'demo/menus/tempura-review.jpg'],
                ['menu_name' => 'Grilled Wagyu', 'price' => 4800, 'menu_category' => 'food', 'description' => 'Tender wagyu served with seasonal vegetables.', 'image' => 'demo/menus/wagyu-steak.jpg'],
                ['menu_name' => 'Matcha Dessert', 'price' => 900, 'menu_category' => 'food', 'description' => 'Matcha cake and ice cream.', 'image' => 'demo/menus/matcha-dessert.jpg'],
                ['menu_name' => 'Premium Sake', 'price' => 1400, 'menu_category' => 'drink', 'description' => 'Premium chilled Japanese sake.', 'image' => 'demo/menus/craft-beer.jpg'],
            ],
            21 => [
                ['menu_name' => 'Yakitori Assortment', 'price' => 2200, 'menu_category' => 'food', 'description' => 'Eight charcoal-grilled chicken skewers.', 'image' => 'demo/menus/yakitori.jpg'],
                ['menu_name' => 'Wagyu Skewer', 'price' => 1600, 'menu_category' => 'food', 'description' => 'Grilled wagyu and vegetables.', 'image' => 'demo/menus/wagyu-steak.jpg'],
                ['menu_name' => 'Local Craft Beer', 'price' => 750, 'menu_category' => 'drink', 'description' => 'Tokyo craft beer.', 'image' => 'demo/menus/craft-beer.jpg'],
            ],
        ];

        $menuId = 1;
        $firstFoodMenuIds = [];

        foreach ($menuDefinitions as $restaurantId => $items) {
            foreach ($items as $item) {
                DB::table('menus')->insert([
                    'id' => $menuId,
                    'restaurant_id' => $restaurantId,
                    'menu_name' => $item['menu_name'],
                    'price' => $item['price'],
                    'menu_category' => $item['menu_category'],
                    'description' => $item['description'],
                    'image_path' => $item['image'],
                    'menu_image' => $item['image'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if (
                    $item['menu_category'] === 'food'
                    && !isset($firstFoodMenuIds[$restaurantId])
                ) {
                    $firstFoodMenuIds[$restaurantId] = $menuId;
                }

                $menuId++;
            }
        }

        $photoDefinitions = [
            1 => [
                ['menu' => null, 'path' => 'demo/photos/sushi-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/sushi-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/omakase-sushi.jpg', 'category' => 'food'],
                ['menu' => 'first_food', 'path' => 'demo/menus/wagyu-steak.jpg', 'category' => 'food'],
                ['menu' => null, 'path' => 'demo/menus/matcha-dessert.jpg', 'category' => 'other'],
            ],
            2 => [
                ['menu' => null, 'path' => 'demo/photos/ramen-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/ramen-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/tonkotsu-ramen.jpg', 'category' => 'food'],
                ['menu' => 'first_food', 'path' => 'demo/menus/ramen-review.jpg', 'category' => 'food'],
                ['menu' => null, 'path' => 'demo/menus/craft-beer.jpg', 'category' => 'drink'],
            ],
            6 => [
                ['menu' => null, 'path' => 'demo/photos/tempura-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/tempura-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/tempura-tendon.jpg', 'category' => 'food'],
            ],
            7 => [
                ['menu' => null, 'path' => 'demo/photos/sushi-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/sushi-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/steamed-dumplings.jpg', 'category' => 'food'],
            ],
            8 => [
                ['menu' => null, 'path' => 'demo/photos/ramen-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/ramen-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/matcha-dessert.jpg', 'category' => 'food'],
            ],
            9 => [
                ['menu' => null, 'path' => 'demo/photos/tempura-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/tempura-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/yakitori.jpg', 'category' => 'food'],
            ],
            10 => [
                ['menu' => null, 'path' => 'demo/photos/sushi-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/sushi-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/seafood-paella.jpg', 'category' => 'food'],
            ],
            11 => [
                ['menu' => null, 'path' => 'demo/photos/ramen-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/ramen-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/margherita-pizza.jpg', 'category' => 'food'],
            ],
            12 => [
                ['menu' => null, 'path' => 'demo/photos/tempura-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/tempura-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/wagyu-steak.jpg', 'category' => 'food'],
            ],
            13 => [
                ['menu' => null, 'path' => 'demo/photos/sushi-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/sushi-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/seafood-paella.jpg', 'category' => 'food'],
            ],
            14 => [
                ['menu' => null, 'path' => 'demo/photos/ramen-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/ramen-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/wagyu-steak.jpg', 'category' => 'food'],
            ],
            15 => [
                ['menu' => null, 'path' => 'demo/photos/tempura-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/tempura-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/matcha-dessert.jpg', 'category' => 'food'],
            ],
            16 => [
                ['menu' => null, 'path' => 'demo/photos/sushi-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/sushi-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/omakase-sushi.jpg', 'category' => 'food'],
            ],
            17 => [
                ['menu' => null, 'path' => 'demo/photos/sushi-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/sushi-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/sushi-review.jpg', 'category' => 'food'],
            ],
            18 => [
                ['menu' => null, 'path' => 'demo/photos/ramen-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/ramen-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/tonkotsu-ramen.jpg', 'category' => 'food'],
            ],
            19 => [
                ['menu' => null, 'path' => 'demo/photos/ramen-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/ramen-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/ramen-review.jpg', 'category' => 'food'],
            ],
            20 => [
                ['menu' => null, 'path' => 'demo/photos/tempura-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/tempura-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/tempura-tendon.jpg', 'category' => 'food'],
                ['menu' => 'first_food', 'path' => 'demo/menus/omakase-sushi.jpg', 'category' => 'food'],
                ['menu' => 'first_food', 'path' => 'demo/menus/wagyu-steak.jpg', 'category' => 'food'],
                ['menu' => null, 'path' => 'demo/menus/matcha-dessert.jpg', 'category' => 'other'],
            ],
            21 => [
                ['menu' => null, 'path' => 'demo/photos/tempura-exterior.jpg', 'category' => 'exterior'],
                ['menu' => null, 'path' => 'demo/photos/tempura-interior.jpg', 'category' => 'interior'],
                ['menu' => 'first_food', 'path' => 'demo/menus/yakitori.jpg', 'category' => 'food'],
            ],
        ];

        foreach ($photoDefinitions as $restaurantId => $items) {
            foreach ($items as $item) {
                Photo::create([
                    'restaurant_id' => $restaurantId,
                    'menu_id' => $item['menu'] === 'first_food'
                        ? ($firstFoodMenuIds[$restaurantId] ?? null)
                        : null,
                    'photo_path' => $item['path'],
                    'photo_category' => $item['category'],
                ]);
            }
        }
    }

    private function seedReviewsAndSocialData(): void
    {
        DB::table('likes')->delete();
        DB::table('comments')->delete();
        DB::table('follows')->delete();
        DB::table('favorites')->delete();
        Post::query()->forceDelete();

        $sourceDir = database_path('seeders/assets/demo/reviews');
        $targetDir = storage_path('app/public/demo/reviews');

        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true, true);
        }
        if (File::exists($sourceDir)) {
            $files = File::files($sourceDir);
            foreach ($files as $file) {
                File::copy($file->getPathname(), $targetDir . '/' . $file->getFilename());
            }
        }

        $posts = [
            ['id' => 1, 'user_id' => 2, 'restaurant_id' => 1, 'rating' => 5, 'description' => 'Amazing sushi and thoughtful English-speaking service.', 'image' => 'storage/demo/reviews/sushi-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 2, 'user_id' => 8, 'restaurant_id' => 1, 'rating' => 5, 'description' => 'The omakase was beautifully presented and worth the visit.', 'image' => 'storage/demo/reviews/sushi-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 3, 'user_id' => 9, 'restaurant_id' => 1, 'rating' => 4, 'description' => 'Comfortable interior and excellent seasonal fish.', 'image' => 'storage/demo/reviews/steak-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 4, 'user_id' => 10, 'restaurant_id' => 1, 'rating' => 2, 'description' => 'Reported review sample for moderation.', 'image' => 'storage/demo/reviews/sushi-review.jpg', 'status' => 'visible', 'is_reported' => true],
            ['id' => 5, 'user_id' => 11, 'restaurant_id' => 1, 'rating' => 1, 'description' => 'Hidden review sample.', 'image' => 'storage/demo/reviews/sushi-review.jpg', 'status' => 'hidden', 'is_reported' => true],
            ['id' => 6, 'user_id' => 2, 'restaurant_id' => 2, 'rating' => 5, 'description' => 'Rich broth, fast service, and easy ordering in English.', 'image' => 'storage/demo/reviews/ramen-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 7, 'user_id' => 8, 'restaurant_id' => 2, 'rating' => 4, 'description' => 'Great ramen and crispy gyoza.', 'image' => 'storage/demo/reviews/ramen-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 8, 'user_id' => 9, 'restaurant_id' => 2, 'rating' => 5, 'description' => 'Perfect casual dinner near Shibuya Station.', 'image' => 'storage/demo/reviews/ramen-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 9, 'user_id' => 2, 'restaurant_id' => 20, 'rating' => 5, 'description' => 'A beautiful seasonal course from tempura to matcha dessert.', 'image' => 'storage/demo/reviews/tempura-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 10, 'user_id' => 8, 'restaurant_id' => 20, 'rating' => 5, 'description' => 'Elegant private room and attentive service.', 'image' => 'storage/demo/reviews/steak-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 11, 'user_id' => 9, 'restaurant_id' => 20, 'rating' => 4, 'description' => 'The wagyu and sashimi were excellent.', 'image' => 'storage/demo/reviews/sushi-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 12, 'user_id' => 10, 'restaurant_id' => 6, 'rating' => 4, 'description' => 'Light, crisp tempura in a calm dining room.', 'image' => 'storage/demo/reviews/tempura-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 13, 'user_id' => 11, 'restaurant_id' => 7, 'rating' => 5, 'description' => 'Comforting noodles and friendly service.', 'image' => 'storage/demo/reviews/ramen-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 14, 'user_id' => 12, 'restaurant_id' => 8, 'rating' => 4, 'description' => 'Creative plant-based dishes with good portions.', 'image' => 'storage/demo/reviews/pizza-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 15, 'user_id' => 2, 'restaurant_id' => 9, 'rating' => 5, 'description' => 'A lively izakaya with great shared plates.', 'image' => 'storage/demo/reviews/steak-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 16, 'user_id' => 8, 'restaurant_id' => 10, 'rating' => 4, 'description' => 'Flavorful curry with adjustable spice.', 'image' => 'storage/demo/reviews/tempura-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 17, 'user_id' => 9, 'restaurant_id' => 11, 'rating' => 5, 'description' => 'Excellent pizza and a relaxed atmosphere.', 'image' => 'storage/demo/reviews/pizza-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 18, 'user_id' => 10, 'restaurant_id' => 12, 'rating' => 4, 'description' => 'High-quality wagyu and easy table-side grilling.', 'image' => 'storage/demo/reviews/steak-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 19, 'user_id' => 11, 'restaurant_id' => 13, 'rating' => 5, 'description' => 'Aromatic curry and helpful staff.', 'image' => 'storage/demo/reviews/tempura-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 20, 'user_id' => 12, 'restaurant_id' => 14, 'rating' => 4, 'description' => 'A cozy bistro for a special dinner.', 'image' => 'storage/demo/reviews/steak-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 21, 'user_id' => 2, 'restaurant_id' => 15, 'rating' => 5, 'description' => 'Fresh bread and a pleasant morning cafe.', 'image' => 'storage/demo/reviews/pizza-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 22, 'user_id' => 8, 'restaurant_id' => 16, 'rating' => 4, 'description' => 'Beautiful Ginza sushi at a welcoming counter.', 'image' => 'storage/demo/reviews/sushi-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 23, 'user_id' => 9, 'restaurant_id' => 17, 'rating' => 5, 'description' => 'A convenient family-friendly sushi restaurant.', 'image' => 'storage/demo/reviews/sushi-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 24, 'user_id' => 10, 'restaurant_id' => 18, 'rating' => 4, 'description' => 'The black garlic ramen was deeply flavorful.', 'image' => 'storage/demo/reviews/ramen-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 25, 'user_id' => 11, 'restaurant_id' => 19, 'rating' => 5, 'description' => 'Great vegetarian ramen option in Shinjuku.', 'image' => 'storage/demo/reviews/ramen-review.jpg', 'status' => 'visible', 'is_reported' => false],
            ['id' => 26, 'user_id' => 12, 'restaurant_id' => 21, 'rating' => 4, 'description' => 'Juicy yakitori and a good craft beer selection.', 'image' => 'storage/demo/reviews/steak-review.jpg', 'status' => 'visible', 'is_reported' => false],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }

        DB::table('comments')->insert([
            ['id' => 1, 'post_id' => 1, 'user_id' => 8, 'body' => 'I want to try this restaurant too!', 'is_reported' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'post_id' => 6, 'user_id' => 2, 'body' => 'This ramen looks great.', 'is_reported' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'post_id' => 4, 'user_id' => 9, 'body' => 'Reported comment sample.', 'is_reported' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'post_id' => 9, 'user_id' => 10, 'body' => 'The seasonal course looks beautiful.', 'is_reported' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('likes')->insert([
            ['user_id' => 8, 'post_id' => 1],
            ['user_id' => 9, 'post_id' => 1],
            ['user_id' => 2, 'post_id' => 6],
            ['user_id' => 8, 'post_id' => 9],
            ['user_id' => 9, 'post_id' => 9],
        ]);

        DB::table('follows')->insert([
            ['follower_id' => 2, 'following_id' => 8],
            ['follower_id' => 8, 'following_id' => 2],
            ['follower_id' => 9, 'following_id' => 2],
        ]);

        DB::table('favorites')->insert([
            ['user_id' => 2, 'restaurant_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 2, 'restaurant_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 2, 'restaurant_id' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 8, 'restaurant_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 9, 'restaurant_id' => 16, 'created_at' => now(), 'updated_at' => now()],
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

    // private function seedNotifications(): void
    // {
    //     DB::table('notifications')->delete();

    //     $admin = User::where('email', 'admin@example.com')->firstOrFail();
    //     $customer = User::where('email', 'customer@example.com')->firstOrFail();
    //     $restaurantOwner = User::where('email', 'restaurant@example.com')->firstOrFail();

    //     DB::table('notifications')->insert([
    //         [
    //             'id' => (string) Str::uuid(),
    //             'type' => 'demo.restaurant.application',
    //             'notifiable_type' => User::class,
    //             'notifiable_id' => $admin->id,
    //             'data' => json_encode([
    //                 'title' => 'New Restaurant Application',
    //                 'message' => 'Yakitori Tori is waiting for approval.',
    //                 'url' => route('admin.restaurants.pending'),
    //                 'button_text' => 'Review Application',
    //             ]),
    //             'read_at' => null,
    //             'created_at' => now()->subHours(3),
    //             'updated_at' => now()->subHours(3),
    //         ],
    //         [
    //             'id' => (string) Str::uuid(),
    //             'type' => 'demo.reservation.submitted',
    //             'notifiable_type' => User::class,
    //             'notifiable_id' => $restaurantOwner->id,
    //             'data' => json_encode([
    //                 'title' => 'New Reservation Request',
    //                 'message' => 'John Doe requested a reservation at Sushi Masaru.',
    //                 'reservation_code' => 'RM005',
    //                 'url' => route('restaurant.reservations'),
    //                 'button_text' => 'View Reservations',
    //             ]),
    //             'read_at' => null,
    //             'created_at' => now()->subMinutes(30),
    //             'updated_at' => now()->subMinutes(30),
    //         ],
    //         [
    //             'id' => (string) Str::uuid(),
    //             'type' => 'demo.contact.reply',
    //             'notifiable_type' => User::class,
    //             'notifiable_id' => $customer->id,
    //             'data' => json_encode([
    //                 'title' => 'Admin replied to your inquiry',
    //                 'message' => 'Please check the contact page for details.',
    //                 'url' => route('customer.contact.index'),
    //                 'button_text' => 'Open Contact',
    //             ]),
    //             'read_at' => now()->subMinutes(20),
    //             'created_at' => now()->subDay(),
    //             'updated_at' => now()->subDay(),
    //         ],
    //     ]);
    // }
}
