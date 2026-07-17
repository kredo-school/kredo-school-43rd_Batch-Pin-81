<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

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
        abort_unless($restaurant->status === Restaurant::STATUS_APPROVED, 404);

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

        $realReviews = \App\Models\Post::with(['user', 'likes', 'comments.user'])
            ->where('restaurant_id', $restaurant->id)
            ->latest()
            ->get();

        $totalCount = $realReviews->count();

        $averageRating = $totalCount > 0 ? round($realReviews->avg('rating'), 1) : 4.8;
        
        $starsData = [
            5 => ['count' => 172, 'percentage' => 70],
            4 => ['count' => 49,  'percentage' => 20],
            3 => ['count' => 12,  'percentage' => 5],
            2 => ['count' => 12,  'percentage' => 5],
            1 => ['count' => 12,  'percentage' => 5],
        ];

        if ($totalCount > 0) {
            $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
            foreach ($realReviews as $r) {
                $rating = $r->rating ?? 5;
                if (isset($counts[$rating])) {
                    $counts[$rating]++;
                }
            }
            foreach ($counts as $star => $count) {
                $starsData[$star] = [
                    'count' => $count,
                    'percentage' => round(($count / $totalCount) * 100)
                ];
            }
            $statsTotalReviews = $totalCount;
            $reviewCollection = $realReviews;
        } else {
            $statsTotalReviews = 0;
            $reviewCollection = collect([]);
        }

        $reportedCount = \App\Models\Post::where('restaurant_id', $restaurant->id)
            ->where('is_reported', true)
            ->count();

        $stats = [
            'average_rating' => $averageRating,
            'total_reviews'  => $statsTotalReviews,
            'stars'          => $starsData,
            'reported_count' => $reportedCount
        ];

        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $currentItems = $reviewCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $reviews = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $reviewCollection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return view('customers.restaurants.show', compact('restaurant', 'availableSlots', 'reviews', 'stats'));
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
