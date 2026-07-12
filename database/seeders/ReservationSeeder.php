<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reservation::query()->forceDelete();

        $today = Carbon::today();

        $reservations = [
            [
                'id' => 1,
                'user_id' => 2,
                'restaurant_id' => 1,
                'table_id' => 1,
                'num_of_people' => 2,
                'reservation_date' => $today->toDateString(),
                'reservation_time' => '18:00:00',
                'end_time' => '20:00:00',
                'status' => 'pending',
                'cancelled_by' => null,
            ],
            [
                'id' => 2,
                'user_id' => 8,
                'restaurant_id' => 1,
                'table_id' => 2,
                'num_of_people' => 4,
                'reservation_date' => $today->toDateString(),
                'reservation_time' => '19:30:00',
                'end_time' => '21:30:00',
                'status' => 'confirmed',
                'cancelled_by' => null,
            ],
            [
                'id' => 3,
                'user_id' => 9,
                'restaurant_id' => 1,
                'table_id' => 3,
                'num_of_people' => 6,
                'reservation_date' => $today->copy()->addDay()->toDateString(),
                'reservation_time' => '12:00:00',
                'end_time' => '14:00:00',
                'status' => 'confirmed',
                'cancelled_by' => null,
            ],
            [
                'id' => 4,
                'user_id' => 10,
                'restaurant_id' => 2,
                'table_id' => 5,
                'num_of_people' => 2,
                'reservation_date' => $today->toDateString(),
                'reservation_time' => '18:15:00',
                'end_time' => '19:45:00',
                'status' => 'pending',
                'cancelled_by' => null,
            ],
            [
                'id' => 5,
                'user_id' => 11,
                'restaurant_id' => 2,
                'table_id' => 6,
                'num_of_people' => 4,
                'reservation_date' => $today->copy()->subDay()->toDateString(),
                'reservation_time' => '20:00:00',
                'end_time' => '21:30:00',
                'status' => 'completed',
                'cancelled_by' => null,
            ],
            [
                'id' => 6,
                'user_id' => 12,
                'restaurant_id' => 2,
                'table_id' => 6,
                'num_of_people' => 3,
                'reservation_date' => $today->copy()->addDays(2)->toDateString(),
                'reservation_time' => '19:00:00',
                'end_time' => '20:30:00',
                'status' => 'cancelled',
                'cancelled_by' => 'customer',
            ],
            [
                'id' => 7,
                'user_id' => 2,
                'restaurant_id' => 1,
                'table_id' => 2,
                'num_of_people' => 4,
                'reservation_date' => $today->copy()->addDays(3)->toDateString(),
                'reservation_time' => '17:30:00',
                'end_time' => '19:30:00',
                'status' => 'cancelled',
                'cancelled_by' => 'restaurant',
            ],
        ];

        foreach ($reservations as $reservation) {
            DB::table('reservations')->insert(array_merge($reservation, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
