<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Notifications\ChangedReservationNotification;
use App\Notifications\CustomerRunningLateNotification;
use App\Services\ReservationAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MyReservationController extends Controller
{
    public function __construct(
        private readonly ReservationAvailabilityService $availabilityService
    ) {}

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

    public function edit(Reservation $reservation): View
    {
        $this->ensureReservationOwner($reservation);

        return view('customers.my_reservations.modals.edit', [
            'booking' => (object) [
                'id' => $reservation->id,
                'date' => $reservation->reservation_date->format('Y-m-d'),
                'time' => Carbon::parse($reservation->reservation_time)->format('H:i'),
                'guests' => $reservation->num_of_people,
                'reservation_date' => $reservation->reservation_date,
                'reservation_time' => Carbon::parse($reservation->reservation_time)->format('H:i'),
                'num_of_people' => $reservation->num_of_people,
                'reservation_code' => $reservation->reservation_code,
            ],
        ]);
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

    public function update(
        Request $request,
        Reservation $reservation
    ): RedirectResponse {
        $this->ensureReservationOwner($reservation);

        $previousReservation = [
            'reservation_date' => $reservation->reservation_date instanceof \Illuminate\Support\Carbon
                ? $reservation->reservation_date->format('Y-m-d')
                : (string) $reservation->reservation_date,
            'reservation_time' => Carbon::parse($reservation->reservation_time)->format('H:i'),
            'num_of_people' => (int) $reservation->num_of_people,
        ];

        $validated = $request->validate([
            'reservation_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
            'reservation_time' => [
                'required',
                'date_format:H:i',
            ],
            'num_of_people' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $reservation->loadMissing('restaurant.user', 'user');
        $restaurantOwner = $reservation->restaurant?->user;

        $start = Carbon::parse(
            $validated['reservation_date'] . ' ' . $validated['reservation_time']
        );

        if ($start->lt(now())) {
            throw ValidationException::withMessages([
                'reservation_time' => 'Please select a future reservation time.',
            ]);
        }

        if (
            $start->minute % ReservationAvailabilityService::SLOT_INTERVAL_MINUTES !== 0
        ) {
            throw ValidationException::withMessages([
                'reservation_time' => 'Reservations are available in 15-minute intervals.',
            ]);
        }

        if (
            !$this->availabilityService->isWithinOperatingHours(
                $reservation->restaurant,
                $validated['reservation_date'],
                $validated['reservation_time']
            )
        ) {
            throw ValidationException::withMessages([
                'reservation_time' => 'The selected time is outside this restaurant\'s operating hours.',
            ]);
        }

        $updatedReservation = DB::transaction(function () use ($reservation, $validated) {
            $table = $this->availabilityService->findAvailableTable(
                $reservation->restaurant,
                $validated['reservation_date'],
                $validated['reservation_time'],
                (int) $validated['num_of_people'],
                $reservation->id,
                true
            );

            if (!$table) {
                throw ValidationException::withMessages([
                    'reservation_time' => 'That time is no longer available. Please select another time.',
                ]);
            }

            $reservation->update([
                'table_id' => $table->id,
                'reservation_date' => $validated['reservation_date'],
                'reservation_time' => $validated['reservation_time'],
                'end_time' => Carbon::parse(
                    $validated['reservation_date'] . ' ' . $validated['reservation_time']
                )
                    ->addMinutes($this->availabilityService->stayMinutes($reservation->restaurant))
                    ->format('H:i:s'),
                'num_of_people' => (int) $validated['num_of_people'],
                'status' => 'pending',
                'cancelled_by' => null,
            ]);

            return $reservation->fresh(['restaurant.user', 'user']);
        });

        if ($restaurantOwner) {
            $restaurantOwner->notify(
                new ChangedReservationNotification($updatedReservation, [
                    'reservation_date' => [
                        'label' => 'Date',
                        'before' => $previousReservation['reservation_date'],
                        'after' => $updatedReservation->reservation_date->format('Y-m-d'),
                    ],
                    'reservation_time' => [
                        'label' => 'Time',
                        'before' => $previousReservation['reservation_time'],
                        'after' => Carbon::parse($updatedReservation->reservation_time)->format('H:i'),
                    ],
                    'num_of_people' => [
                        'label' => 'Guests',
                        'before' => $previousReservation['num_of_people'],
                        'after' => (int) $updatedReservation->num_of_people,
                    ],
                ])
            );
        }

        return redirect()
            ->route('my_reservations')
            ->with('success', 'Reservation updated and sent to the restaurant for approval.');
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
