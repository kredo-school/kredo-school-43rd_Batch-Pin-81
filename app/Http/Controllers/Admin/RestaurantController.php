<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Notifications\RestaurantApplicationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
  public function index(Request $request)
  {
    return $this->renderIndex($request);
  }

  public function pending(Request $request)
  {
    return $this->renderIndex($request, Restaurant::STATUS_PENDING);
  }

  public function approved(Request $request)
  {
    return $this->renderIndex($request, Restaurant::STATUS_APPROVED);
  }

  public function rejected(Request $request)
  {
    return $this->renderIndex($request, Restaurant::STATUS_REJECTED);
  }

  public function suspended(Request $request)
  {
    return $this->renderIndex($request, Restaurant::STATUS_SUSPENDED);
  }

  private function renderIndex(Request $request, ?string $status = null)
  {
    $search = trim((string) $request->query('search', ''));

    $query = Restaurant::with('user');

    if ($status !== null) {
      $query->where('status', $status);
    }

    if ($search !== '') {
      $query->where(function (Builder $restaurantQuery) use ($search) {
        $restaurantQuery->where('restaurant_name', 'like', '%' . $search . '%')
          ->orWhere('description', 'like', '%' . $search . '%')
          ->orWhere('postal_code', 'like', '%' . $search . '%')
          ->orWhere('prefecture', 'like', '%' . $search . '%')
          ->orWhere('city', 'like', '%' . $search . '%')
          ->orWhere('street_address_building', 'like', '%' . $search . '%')
          ->orWhere('phone_number', 'like', '%' . $search . '%')
          ->orWhere('status', 'like', '%' . $search . '%')
          ->orWhereHas('user', function (Builder $userQuery) use ($search) {
            $userQuery->where('first_name', 'like', '%' . $search . '%')
              ->orWhere('last_name', 'like', '%' . $search . '%')
              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%'])
              ->orWhere('email', 'like', '%' . $search . '%');
          });
      });
    }

    $restaurants = $query
      ->orderBy('created_at', 'desc')
      ->get();

    return view('admin.restaurants.index', compact('restaurants', 'search'));
  }

  public function approve(Request $request, Restaurant $restaurant)
  {
    $this->updateRestaurantStatus($restaurant, Restaurant::STATUS_APPROVED);

    if ($request->filled('notification_id')) {
      DB::table('notifications')
        ->where('id', $request->notification_id)
        ->update(['read_at' => now()]);
    }

    return back()->with('success', 'Restaurant approved.');
  }

  public function reject(Request $request, Restaurant $restaurant)
  {
    $this->updateRestaurantStatus($restaurant, Restaurant::STATUS_REJECTED);

    $restaurant->user->notify(
      new RestaurantApplicationStatus(
        'rejected',
        $restaurant
      )
    );

    if ($request->filled('notification_id')) {
      DB::table('notifications')
        ->where('id', $request->notification_id)
        ->update(['read_at' => now()]);
    }

    return back()->with('success', 'Restaurant rejected.');
  }

  public function updateStatus(Request $request, Restaurant $restaurant)
  {
    $request->validate([
      'status' => 'required|string|in:pending,approved,rejected,suspended',
    ]);

    $this->updateRestaurantStatus($restaurant, $request->status);

    return back()->with('success', 'Restaurant status updated successfully.');
  }

  private function updateRestaurantStatus(Restaurant $restaurant, string $status): void
  {
    $restaurant->update([
      'status' => $status,
    ]);

    if ($status === Restaurant::STATUS_APPROVED) {
      $restaurant->user->update([
        'role_id' => 2,
      ]);

      $restaurant->user->notify(
        new RestaurantApplicationStatus(
          'approved',
          $restaurant
        )
      );
      return;
    }

    if (in_array($status, [Restaurant::STATUS_PENDING, Restaurant::STATUS_REJECTED, Restaurant::STATUS_SUSPENDED], true)) {
      $restaurant->user->update([
        'role_id' => 1,
      ]);
    }
  }

  public function show(Restaurant $restaurant)
  {
    $restaurant->load('user');

    return view('admin.restaurants.show', compact('restaurant'));
  }

  public function edit(Restaurant $restaurant)
  {
    $restaurant->load('user');

    return view('admin.restaurants.edit', compact('restaurant'));
  }

  public function update(Request $request, Restaurant $restaurant)
  {
    $request->validate([
      'restaurant_name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'address' => 'required|string|max:255',
      'phone_number' => 'required|string|max:30',
      'website' => 'nullable|string|max:255',
      'instagram' => 'nullable|string|max:255',
      'facebook' => 'nullable|string|max:255',
      'twitter' => 'nullable|string|max:255',
      'capacity' => 'nullable|integer',
    ]);

    $hours = $request->hours ?? [];
    $cleaned = [];

    foreach ($hours as $day => $data) {
      $opens = $data['open'] ?? [];
      $closes = $data['close'] ?? [];

      foreach ($opens as $i => $open) {
        $close = $closes[$i] ?? null;

        if (!$open || !$close) {
          continue;
        }

        $cleaned[$day][] = [
          'open' => $open,
          'close' => $close,
        ];
      }
    }

    $restaurant->update([
      'restaurant_name' => $request->restaurant_name,
      'description' => $request->description,
      'address' => $request->address,
      'phone_number' => $request->phone_number,
      'website' => $request->website,
      'instagram' => $request->instagram,
      'facebook' => $request->facebook,
      'twitter' => $request->twitter,
      'capacity' => $request->capacity,
      'operating_hours' => $cleaned,
    ]);

    return redirect()
      ->route('admin.restaurants.show', $restaurant)
      ->with('success', 'Restaurant updated successfully.');
  }

  public function destroy(Restaurant $restaurant)
  {
    $restaurant->delete();

    return back()->with('success', 'Restaurant deleted successfully.');
  }
}
