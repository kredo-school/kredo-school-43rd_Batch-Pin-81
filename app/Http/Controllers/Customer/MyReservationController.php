<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Notifications\CustomerRunningLateNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyReservationController extends Controller
{
    public function index(): View
    {
        $reservations = Reservation::query()
            ->with(['restaurant.photos'])
            ->where('user_id', Auth::id())
            ->whereNotIn('status', ['cancelled', 'canceled'])
            ->get();

        $upcomingReservations = $reservations
            ->filter(function (Reservation $reservation): bool {
                return $reservation->status !== 'completed'
                    && $this->reservationDateTime($reservation)
                    ->greaterThanOrEqualTo(now());
            })
            ->sortBy(function (Reservation $reservation): int {
                return $this->reservationDateTime($reservation)->timestamp;
            })
            ->map(function (Reservation $reservation): object {
                return $this->formatForView($reservation);
            })
            ->values();

        $pastReservations = $reservations
            ->filter(function (Reservation $reservation): bool {
                return $reservation->status === 'completed'
                    || $this->reservationDateTime($reservation)
                    ->lessThan(now());
            })
            ->sortByDesc(function (Reservation $reservation): int {
                return $this->reservationDateTime($reservation)->timestamp;
            })
            ->map(function (Reservation $reservation): object {
                return $this->formatForView($reservation);
            })
            ->values();

        return view(
            'customers.my_reservations.index',
            compact('upcomingReservations', 'pastReservations')
        );
    }

    public function notifyLate(
        Request $request,
        Reservation $reservation
    ): RedirectResponse {
        $this->ensureReservationOwner($reservation);

        $validated = $request->validate([
            'late_minutes' => ['required', 'integer', 'in:10,15'],
        ]);

        $lateMinutes = (int) $validated['late_minutes'];

        $reservation->loadMissing([
            'restaurant.user',
            'user',
        ]);

        $restaurantOwner = $reservation->restaurant?->user;

        abort_unless(
            $restaurantOwner,
            404,
            'Restaurant owner not found.'
        );

        $restaurantOwner->notify(
            new CustomerRunningLateNotification($reservation, $lateMinutes)
        );

        return redirect()
            ->back()
            ->with(
                'success',
                'The restaurant has been notified that you will be late by ' . $lateMinutes . ' minutes.'
            );
    }

    public function destroy(
        Reservation $reservation
    ): RedirectResponse {
        $this->ensureReservationOwner($reservation);

        $reservation->update([
            'status' => 'cancelled',
            'cancelled_by' => 'customer',
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Reservation cancelled successfully.'
            );
    }

    private function ensureReservationOwner(
        Reservation $reservation
    ): void {
        abort_unless(
            (int) $reservation->user_id === (int) Auth::id(),
            403
        );
    }

    private function reservationDateTime(
        Reservation $reservation
    ): Carbon {
        return Carbon::parse(
            $reservation->reservation_date->format('Y-m-d')
                . ' '
                . $reservation->reservation_time
        );
    }

    private function formatForView(
        Reservation $reservation
    ): object {
        $restaurant = $reservation->restaurant;
        $photo = null;

        if ($restaurant) {
            $photo = $restaurant->photos
                ->firstWhere('photo_category', 'exterior')
                ?? $restaurant->photos
                ->firstWhere('photo_category', 'interior')
                ?? $restaurant->photos->first();
        }

        $location = collect([
            $restaurant?->city,
            $restaurant?->prefecture,
        ])
            ->filter()
            ->implode(', ');

        return (object) [
            'id' => $reservation->id,
            'restaurant_id' => $reservation->restaurant_id,
            'restaurant_name' =>
            $restaurant?->restaurant_name ?? 'Restaurant',
            'location' => $location !== '' ? $location : '-',
            'reservation_code' =>
            $reservation->reservation_code,
            'date' =>
            $reservation->reservation_date->format('Y-m-d'),
            'time' =>
            Carbon::parse($reservation->reservation_time)
                ->format('H:i'),
            'guests' => $reservation->num_of_people,
            'status' => $reservation->status,
            'restaurant_image' =>
            $this->photoUrl($photo?->photo_path),
        ];
    }

    private function photoUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (
            str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
        ) {
            return $path;
        }

        return asset(
            'storage/' . ltrim($path, '/')
        );
    }
}
