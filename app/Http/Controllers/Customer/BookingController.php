<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\User;
use App\Notifications\ChangedReservationNotification;
use App\Notifications\NewReservationNotification;
use App\Services\ReservationAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;


class BookingController extends Controller
{
    public function __construct(
        private readonly ReservationAvailabilityService $availabilityService
    ) {}

    public function create(
        Request $request,
        Restaurant $restaurant
    ): View {
        $selectedDate = $request->string('date')->toString()
            ?: now()->format('Y-m-d');

        $partySize = max(
            (int) $request->input('guests', 1),
            1
        );

        $selectedTime = $request->string('time')->toString()
            ?: null;

        $availableSlots = $this->availabilityService
            ->generateAvailableStartTimes(
                $restaurant,
                $selectedDate,
                $partySize
            );

        if (
            $selectedTime
            && !in_array($selectedTime, $availableSlots, true)
        ) {
            $selectedTime = null;
        }

        $maxPartySize = (int) (
            $restaurant->tables()
            ->where('is_active', true)
            ->max('capacity') ?? 0
        );

        return view(
            'customers.restaurants.book',
            compact(
                'restaurant',
                'selectedDate',
                'selectedTime',
                'partySize',
                'availableSlots',
                'maxPartySize'
            )
        );
    }

    public function availability(
        Request $request,
        Restaurant $restaurant
    ): JsonResponse {
        $validated = $request->validate([
            'date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
            'guests' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        return response()->json([
            'slots' => $this->availabilityService
                ->generateAvailableStartTimes(
                    $restaurant,
                    $validated['date'],
                    (int) $validated['guests']
                ),
            'duration_minutes' => $this->availabilityService
                ->stayMinutes($restaurant),
            'cleaning_minutes' =>
            ReservationAvailabilityService::CLEANING_MINUTES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'restaurant_id' => [
                'required',
                'exists:restaurants,id',
            ],
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
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],
            'requests' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $restaurant = Restaurant::findOrFail(
            $validated['restaurant_id']
        );

        $start = Carbon::parse(
            $validated['reservation_date']
                . ' '
                . $validated['reservation_time']
        );

        if ($start->lt(now())) {
            throw ValidationException::withMessages([
                'reservation_time' =>
                'Please select a future reservation time.',
            ]);
        }

        if (
            $start->minute
            % ReservationAvailabilityService::SLOT_INTERVAL_MINUTES
            !== 0
        ) {
            throw ValidationException::withMessages([
                'reservation_time' =>
                'Reservations are available in 15-minute intervals.',
            ]);
        }

        if (
            !$this->availabilityService->isWithinOperatingHours(
                $restaurant,
                $validated['reservation_date'],
                $validated['reservation_time']
            )
        ) {
            throw ValidationException::withMessages([
                'reservation_time' =>
                'The selected time is outside this restaurant\'s operating hours.',
            ]);
        }

        $reservation = DB::transaction(
            function () use ($validated, $restaurant) {
                $table = $this->availabilityService
                    ->findAvailableTable(
                        $restaurant,
                        $validated['reservation_date'],
                        $validated['reservation_time'],
                        (int) $validated['num_of_people'],
                        null,
                        true
                    );

                if (!$table) {
                    throw ValidationException::withMessages([
                        'reservation_time' =>
                        'That time is no longer available. Please select another time.',
                    ]);
                }

                $nameParts = preg_split(
                    '/\s+/',
                    trim($validated['name']),
                    2
                );

                $user = Auth::user() ?? User::firstOrCreate(
                    [
                        'email' => $validated['email'],
                    ],
                    [
                        'first_name' => $nameParts[0] ?? 'Guest',
                        'last_name' => $nameParts[1] ?? '',
                        'password' => bcrypt(Str::random(32)),
                        'role_id' => User::ROLE_USER,
                    ]
                );

                $status = $this->availabilityService
                    ->statusForStart(
                        $validated['reservation_date'],
                        $validated['reservation_time']
                    );

                return Reservation::create([
                    'user_id' => $user->id,
                    'restaurant_id' => $restaurant->id,
                    'table_id' => $table->id,
                    'reservation_date' =>
                    $validated['reservation_date'],
                    'reservation_time' =>
                    $validated['reservation_time'],
                    'end_time' => Carbon::parse(
                        $validated['reservation_date']
                            . ' '
                            . $validated['reservation_time']
                    )
                        ->addMinutes(
                            $this->availabilityService
                                ->stayMinutes($restaurant)
                        )
                        ->format('H:i:s'),
                    'num_of_people' =>
                    (int) $validated['num_of_people'],
                    'status' => $status,
                    'booking_source' => 'online',
                ]);
            },
            3
        );

        // 友達が追加した新規予約通知
        $restaurantOwner = $reservation->restaurant->user;

        if ($restaurantOwner) {
            $restaurantOwner->notify(
                new NewReservationNotification($reservation)
            );
        }

        return redirect()->route(
            'booking.confirmation',
            ['reservation' => $reservation]
        );
    }

    public function confirmation(
        Reservation $reservation
    ): View {
        $reservation->load([
            'restaurant',
            'user',
            'table',
        ]);

        return view(
            'customers.restaurants.booking_confirmation',
            compact('reservation')
        );
    }

    public function update(
        Request $request,
        Reservation $reservation
    ): RedirectResponse {
        $validated = $request->validate([
            'reservation_date' => [
                'required',
                'date',
            ],
            'reservation_time' => [
                'required',
            ],
            'num_of_people' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $reservation->update($validated);

        // 友達が追加した予約変更通知
        $restaurantOwner = $reservation->restaurant->user;

        if ($restaurantOwner) {
            $restaurantOwner->notify(
                new ChangedReservationNotification($reservation)
            );
        }

        return redirect()
            ->route('customer.reservations.index')
            ->with(
                'success',
                'Reservation updated successfully.'
            );
    }
}
