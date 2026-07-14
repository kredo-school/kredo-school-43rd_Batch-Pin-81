<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
  public function index(Request $request)
  {
    $currentTab = $request->query('tab', 'all');
    $search = trim((string) $request->query('search', ''));

    $query = Reservation::with(['user', 'restaurant', 'table']);

    match ($currentTab) {
      'pending' => $query->where('status', 'pending'),
      'confirmed' => $query->where('status', 'confirmed'),
      'completed' => $query->where('status', 'completed'),
      'cancelled' => $query->where('status', 'cancelled'),
      default => null,
    };

    if ($search !== '') {
      $query->where(function ($reservationQuery) use ($search) {
        $cleanId = preg_replace('/[^0-9]/', '', $search);

        if ($cleanId !== '') {
          $reservationQuery->orWhere('id', (int) $cleanId);
        }

        $reservationQuery->orWhere('guest_name', 'like', '%' . $search . '%')
          ->orWhere('phone_number', 'like', '%' . $search . '%')
          ->orWhereHas('user', function ($userQuery) use ($search) {
            $userQuery->where('first_name', 'like', '%' . $search . '%')
              ->orWhere('last_name', 'like', '%' . $search . '%')
              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%'])
              ->orWhere('email', 'like', '%' . $search . '%');
          })
          ->orWhereHas('restaurant', function ($restaurantQuery) use ($search) {
            $restaurantQuery->where('restaurant_name', 'like', '%' . $search . '%');
          })
          ->orWhereHas('table', function ($tableQuery) use ($search) {
            $tableQuery->where('table_name', 'like', '%' . $search . '%');
          });
      });
    }

    $reservations = $query
      ->orderBy('reservation_date')
      ->orderBy('reservation_time')
      ->get();

    $counts = [
      'all' => Reservation::count(),
      'pending' => Reservation::where('status', 'pending')->count(),
      'confirmed' => Reservation::where('status', 'confirmed')->count(),
      'completed' => Reservation::where('status', 'completed')->count(),
      'cancelled' => Reservation::where('status', 'cancelled')->count(),
    ];

    return view('admin.reservations', compact('reservations', 'counts', 'currentTab', 'search'));
  }
}
