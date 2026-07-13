<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantSearchController extends Controller
{
    public function view()
    {
        $restaurants = Restaurant::with(['photos', 'categories', 'features'])
            ->approved()
            ->withAvg('posts', 'rating')
            ->get();

        return view('customers.restaurants.index', compact('restaurants'));
    }

    public function show(Restaurant $restaurant)
    {
        abort_unless($restaurant->status === Restaurant::STATUS_APPROVED, 404); // レストランがApprovedされていない場合、万が一カスタマーがURLからレストランページへアクセスしようとした場合は404エラーを返す

        $restaurant->load([
            'photos',
            'categories',
            'features',
            'menus',
            'menus.photos',
            'posts.user',
        ]);

        if ($restaurant->relationLoaded('posts') || $restaurant->posts()->exists()) {
            $restaurant->loadAvg('posts', 'rating')
                ->loadCount('posts');
        } else {
            $restaurant->posts_avg_rating = 4.7;
            $restaurant->posts_count = 120;
        }

        $availableSlots = method_exists($restaurant, 'availableSlots')
            ? $restaurant->availableSlots()
            : collect([]);

        return view('customers.restaurants.show', compact('restaurant', 'availableSlots'));
    }

    public function categories(Request $request)
    {
        $category = $request->query('category');

        $query = Restaurant::with(['photos', 'categories', 'features'])
            ->approved()
            ->withAvg('posts', 'rating');

        if (!empty($category)) {
            $query->where(function ($q) use ($category) {
                $q->where('restaurant_name', 'LIKE', '%' . $category . '%')
                    ->orWhere('description', 'LIKE', '%' . $category . '%');
            });
        }

        $restaurants = $query->get();

        return view('customers.restaurants.index', compact('restaurants', 'category'));
    }

    public function areas(Request $request)
    {
        $area = $request->query('area');

        $query = Restaurant::with(['photos', 'categories', 'features'])
            ->approved()
            ->withAvg('posts', 'rating');

        if (!empty($area)) {
            $query->where('city', 'like', '%' . $area . '%')
                ->orWhere('street_address_building', 'like', '%' . $area . '%');
        }

        $restaurants = $query->get();

        return view('customer.areas', compact('restaurants', 'area'));
    }
}
