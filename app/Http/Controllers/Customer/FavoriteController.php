<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorites;
use App\Models\Restaurant;

class FavoriteController extends Controller
{
    // public function view()
    // {
    //     $favorites = Restaurant::all();

    //     return view('customers.favorites.index', compact('favorites'));
    // }

    public function view()
    {
        $favorites = collect([
            (object)[
                'id' => 1,
                'name' => 'Sushi Masaru',
                'category' => 'Sushi',
                'rating' => 4.8,
                'review_count' => 245,
                'location' => 'Ginza, Tokyo',
                'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
                'image' => 'https://picsum.photos/600/400?random=1',
                'features' => ['English Menu', 'Credit Cards']
            ],
            (object)[
                'id' => 2,
                'name' => 'Ramen Ichiban',
                'category' => 'Ramen',
                'rating' => 4.6,
                'review_count' => 892,
                'location' => 'Shibuya, Tokyo',
                'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
                'image' => 'https://picsum.photos/600/400?random=2',
                'features' => ['Walk-ins Welcome', 'Vegetarian Options']
            ],
            (object)[
                'id' => 3,
                'name' => 'Yakitori Tori',
                'category' => 'Yakitori',
                'rating' => 4.7,
                'review_count' => 421,
                'location' => 'Shinjuku, Tokyo',
                'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
                'image' => 'https://picsum.photos/600/400?random=3',
                'features' => ['Counter Seating', 'Sake Pairing']
            ],
            (object)[
                'id' => 4,
                'name' => 'Tempura Kondo',
                'category' => 'Tempura',
                'rating' => 4.9,
                'review_count' => 367,
                'location' => 'Asakusa, Tokyo',
                'available_times' => ['18:00', '18:30', '19:00', '20:15', '20:15', '20:15'],
                'image' => 'https://picsum.photos/600/400?random=4',
                'features' => ['Reservation Required', 'English Menu']
            ],
        ]);

        return view('customers.favorites.index', compact('favorites'));
    }

    public function destroy()
    {
        return redirect()
            ->back()
            ->with('success', 'Favorite restaurant deleted.');
    }
    public function search()
    {
        return view('favorites.index');
    }
}
