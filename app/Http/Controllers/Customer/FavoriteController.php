<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
  public function view(Request $request)
  {
    $filterCategories = $this->filterCategories();
    $filterFeatures = $this->filterFeatures();

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

    $selectedCategories = collect($request->input('cuisines', []))->filter()->values();
    $selectedFeatures = collect($request->input('features', []))->filter()->values();
    $minimumRating = $request->filled('rating')
      ? (float) rtrim((string) $request->input('rating'), '+')
      : null;
    $distanceLimit = $this->distanceLimitInKm($request->input('distance'));
    $originLatitude = $request->filled('origin_latitude') ? (float) $request->input('origin_latitude') : null;
    $originLongitude = $request->filled('origin_longitude') ? (float) $request->input('origin_longitude') : null;

    $favorites = $favorites->filter(function (Restaurant $restaurant) use ($selectedCategories, $selectedFeatures, $minimumRating, $distanceLimit, $originLatitude, $originLongitude) {
      if ($selectedCategories->isNotEmpty()) {
        $restaurantCategories = $restaurant->categories->pluck('category_name');

        if ($restaurantCategories->intersect($selectedCategories)->isEmpty()) {
          return false;
        }
      }

      if ($selectedFeatures->isNotEmpty()) {
        $restaurantFeatures = $restaurant->features->pluck('feature_name');

        if ($restaurantFeatures->intersect($selectedFeatures)->isEmpty()) {
          return false;
        }
      }

      if ($minimumRating !== null && (float) ($restaurant->posts_avg_rating ?? 0) < $minimumRating) {
        return false;
      }

      if ($distanceLimit !== null) {
        if ($originLatitude !== null && $originLongitude !== null) {
          if ($restaurant->latitude === null || $restaurant->longitude === null) {
            return false;
          }

          $distance = $this->distanceInKm(
            $originLatitude,
            $originLongitude,
            (float) $restaurant->latitude,
            (float) $restaurant->longitude
          );

          if ($distance > $distanceLimit) {
            return false;
          }
        }
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

    return view('customers.favorites.index', compact('favorites', 'filterCategories', 'filterFeatures'));
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
}
