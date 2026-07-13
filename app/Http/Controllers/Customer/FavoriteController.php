<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class FavoriteController extends Controller
{
    public function view(Request $request)
    {
        $favorites = Restaurant::query()
            ->select('restaurants.*')
            ->join('favorites', 'favorites.restaurant_id', '=', 'restaurants.id')
            ->where('favorites.user_id', Auth::id())
            ->whereNull('favorites.deleted_at')
            ->with([
                'photos' => fn($query) => $query->orderBy('id'),
                'categories',
                'features',
            ])
            ->withAvg('posts', 'rating')
            ->withCount('posts')
            ->orderByDesc('favorites.created_at')
            ->get();

        $selectedCuisines = collect($request->input('cuisines', []))->filter()->values();
        $selectedFeatures = collect($request->input('features', []))->filter()->values();
        $minimumRating = $request->filled('rating')
            ? (float) rtrim((string) $request->input('rating'), '+')
            : null;

        $favorites = $favorites->filter(function (Restaurant $restaurant) use ($selectedCuisines, $selectedFeatures, $minimumRating) {
            if ($selectedCuisines->isNotEmpty()) {
                $restaurantCuisines = $restaurant->categories->pluck('category_name');

                if ($restaurantCuisines->intersect($selectedCuisines)->isEmpty()) {
                    return false;
                }
            }

            if ($selectedFeatures->isNotEmpty()) {
                $restaurantFeatures = collect($restaurant->feature_labels ?? []);

                if ($restaurantFeatures->intersect($selectedFeatures)->isEmpty()) {
                    return false;
                }
            }

            if ($minimumRating !== null && (float) ($restaurant->posts_avg_rating ?? 0) < $minimumRating) {
                return false;
            }

            return true;
        })->values();

        $favorites->each(function (Restaurant $restaurant): void {
            $featureCollection = $restaurant->getRelation('features');
            $startTime = Carbon::now()->ceilMinute(15);
            $endTime = $startTime->copy()->addHour();
            $currentTime = $startTime->copy();
            $availableTimes = [];

            while ($currentTime <= $endTime) {
                $availableTimes[] = $currentTime->format('H:i');
                $currentTime->addMinutes(15);
            }

            $restaurant->name = $restaurant->restaurant_name;
            $restaurant->category = $restaurant->categories->first()?->category_name ?? '-';
            $restaurant->location = trim(sprintf(
                '%s %s %s %s',
                $restaurant->postal_code ?? '',
                $restaurant->prefecture ?? '',
                $restaurant->city ?? '',
                $restaurant->street_address_building ?? ''
            ));
            $restaurant->rating = number_format((float) ($restaurant->posts_avg_rating ?? 0), 1);
            $restaurant->review_count = $restaurant->posts_count ?? 0;
            $restaurant->available_times = $availableTimes;
            $restaurant->feature_labels = $featureCollection
                ? $featureCollection->pluck('feature_name')->all()
                : [];
        });

        return view('customers.favorites.index', compact('favorites'));
    }

    public function store($id)
    {
        $existingFavorite = DB::table('favorites')
            ->where('user_id', Auth::id())
            ->where('restaurant_id', $id)
            ->first();

        if ($existingFavorite) {
            if ($existingFavorite->deleted_at !== null) {
                DB::table('favorites')
                    ->where('user_id', Auth::id())
                    ->where('restaurant_id', $id)
                    ->update([
                        'deleted_at' => null,
                        'updated_at' => now(),
                    ]);
            }
        } else {
            DB::table('favorites')->insert([
                'user_id' => Auth::id(),
                'restaurant_id' => $id,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Favorite restaurant saved.');
    }

    public function destroy($id)
    {
        DB::table('favorites')
            ->where('user_id', Auth::id())
            ->where('restaurant_id', $id)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect()
            ->back()
            ->with('success', 'Favorite restaurant deleted.');
    }
    public function search()
    {
        return view('favorites.index');
    }
}
