<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestaurantSearchController extends Controller
{
    public function view(Request $request)
    {
        $selectedCategories = collect($request->input('cuisines', []))->filter()->values();
        $selectedFeatures = collect($request->input('features', []))->filter()->values();
        $minimumRating = $request->filled('rating')
            ? (float) rtrim((string) $request->input('rating'), '+')
            : null;
        $distanceLimit = $this->distanceLimitInKm($request->input('distance'));
        $originLatitude = $request->filled('origin_latitude') ? (float) $request->input('origin_latitude') : null;
        $originLongitude = $request->filled('origin_longitude') ? (float) $request->input('origin_longitude') : null;

        $restaurants = Restaurant::with([
            'photos',
            'categories',
            'features'
        ])
            ->select('restaurants.*')
            ->selectRaw(
                'exists (
                    select 1
                    from favorites
                    where favorites.restaurant_id = restaurants.id
                      and favorites.user_id = ?
                      and favorites.deleted_at is null
                ) as is_favorited',
                [Auth::id()]
            )
            ->approved()
            ->withAvg('posts', 'rating');

        if ($selectedCategories->isNotEmpty()) {
            $restaurants->whereHas('categories', function ($query) use ($selectedCategories) {
                $query->whereIn('category_name', $selectedCategories);
            });
        }

        if ($selectedFeatures->isNotEmpty()) {
            $restaurants->whereHas('features', function ($query) use ($selectedFeatures) {
                $query->whereIn('feature_name', $selectedFeatures);
            });
        }

        $restaurants = $restaurants->get();

        if ($minimumRating !== null) {
            $restaurants = $restaurants->filter(function (Restaurant $restaurant) use ($minimumRating) {
                return (float) ($restaurant->posts_avg_rating ?? 0) >= $minimumRating;
            })->values();
        }

        if ($distanceLimit !== null && $originLatitude !== null && $originLongitude !== null) {
            $restaurants = $restaurants->filter(function (Restaurant $restaurant) use ($distanceLimit, $originLatitude, $originLongitude) {
                if ($restaurant->latitude === null || $restaurant->longitude === null) {
                    return false;
                }

                return $this->distanceInKm(
                    $originLatitude,
                    $originLongitude,
                    (float) $restaurant->latitude,
                    (float) $restaurant->longitude
                ) <= $distanceLimit;
            })->values();
        }

        $filterCategories = $this->filterCategories();
        $filterFeatures = $this->filterFeatures();

        return view('customers.restaurants.index', compact('restaurants', 'filterCategories', 'filterFeatures'));
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

        $restaurant->setAttribute('is_favorited', Auth::check()
            ? DB::table('favorites')
                ->where('user_id', Auth::id())
                ->where('restaurant_id', $restaurant->id)
                ->whereNull('deleted_at')
                ->exists()
            : false);

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
                $q->whereHas('categories', function ($categoryQuery) use ($category) {
                    $categoryQuery->where(
                        'category_name',
                        'LIKE',
                        '%' . $category . '%'
                    );
                })
                    ->orWhere('restaurant_name', 'LIKE', '%' . $category . '%')
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
            $query->where(function ($q) use ($area) {
                $q->where('city', 'LIKE', '%' . $area . '%')
                    ->orWhere(
                        'street_address_building',
                        'LIKE',
                        '%' . $area . '%'
                    );
            });
        }

        $restaurants = $query->get();

        return view('customer.areas', compact('restaurants', 'area'));
    }

    private function filterCategories(): array
    {
        $categories = Category::query()
            ->orderBy('category_name')
            ->pluck('category_name')
            ->all();

        return !empty($categories)
            ? $categories
            : [
                'Japanese',
                'Korean',
                'Italian',
                'Chinese',
                'French',
                'Cafe',
            ];
    }

    private function filterFeatures(): array
    {
        $features = Feature::query()
            ->orderBy('feature_name')
            ->pluck('feature_name')
            ->all();

        return !empty($features)
            ? $features
            : [
                'English Menu',
                'Online Payment',
                'Credit Cards',
                'Takeout Available',
                'Free Wi-Fi',
                'Parking Available',
            ];
    }

    private function distanceLimitInKm(?string $distance): ?float
    {
        return match ($distance) {
            'Within 1 km' => 1.0,
            'Within 5 km' => 5.0,
            'Within 10 km' => 10.0,
            default => null,
        };
    }

    private function distanceInKm(float $fromLatitude, float $fromLongitude, float $toLatitude, float $toLongitude): float
    {
        $earthRadius = 6371;

        $latitudeDelta = deg2rad($toLatitude - $fromLatitude);
        $longitudeDelta = deg2rad($toLongitude - $fromLongitude);

        $a = sin($latitudeDelta / 2) ** 2
            + cos(deg2rad($fromLatitude))
            * cos(deg2rad($toLatitude))
            * sin($longitudeDelta / 2) ** 2;

        return $earthRadius * (2 * asin(min(1, sqrt($a))));
    }
}
