<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        Reservation::query()->forceDelete();

        $reservations = [
            ['id' => 1, 'user_id' => 2, 'restaurant_id' => 2, 'table_id' => 5, 'num_of_people' => 2, 'reservation_date' => '2026-07-16', 'reservation_time' => '18:00:00', 'end_time' => '19:30:00', 'status' => 'completed', 'cancelled_by' => null, 'guest_name' => null, 'phone_number' => null, 'booking_source' => 'online'],
            ['id' => 2, 'user_id' => 8, 'restaurant_id' => 1, 'table_id' => 1, 'num_of_people' => 2, 'reservation_date' => '2026-07-16', 'reservation_time' => '11:30:00', 'end_time' => '13:30:00', 'status' => 'completed', 'cancelled_by' => null, 'guest_name' => null, 'phone_number' => null, 'booking_source' => 'online'],
            ['id' => 3, 'user_id' => 2, 'restaurant_id' => 20, 'table_id' => 57, 'num_of_people' => 2, 'reservation_date' => '2026-07-17', 'reservation_time' => '12:00:00', 'end_time' => '14:00:00', 'status' => 'completed', 'cancelled_by' => null, 'guest_name' => null, 'phone_number' => null, 'booking_source' => 'online'],
            ['id' => 4, 'user_id' => 2, 'restaurant_id' => 1, 'table_id' => 2, 'num_of_people' => 4, 'reservation_date' => '2026-07-17', 'reservation_time' => '19:00:00', 'end_time' => '21:00:00', 'status' => 'cancelled', 'cancelled_by' => 'customer', 'guest_name' => null, 'phone_number' => null, 'booking_source' => 'online'],
            ['id' => 5, 'user_id' => 2, 'restaurant_id' => 1, 'table_id' => 1, 'num_of_people' => 2, 'reservation_date' => '2026-07-18', 'reservation_time' => '11:30:00', 'end_time' => '13:30:00', 'status' => 'pending', 'cancelled_by' => null, 'guest_name' => null, 'phone_number' => null, 'booking_source' => 'online'],
            ['id' => 6, 'user_id' => 8, 'restaurant_id' => 1, 'table_id' => 2, 'num_of_people' => 4, 'reservation_date' => '2026-07-18', 'reservation_time' => '12:00:00', 'end_time' => '14:00:00', 'status' => 'confirmed', 'cancelled_by' => null, 'guest_name' => null, 'phone_number' => null, 'booking_source' => 'online'],
            ['id' => 7, 'user_id' => 2, 'restaurant_id' => 2, 'table_id' => 5, 'num_of_people' => 2, 'reservation_date' => '2026-07-18', 'reservation_time' => '13:15:00', 'end_time' => '14:45:00', 'status' => 'confirmed', 'cancelled_by' => null, 'guest_name' => null, 'phone_number' => null, 'booking_source' => 'online'],
            ['id' => 8, 'user_id' => 9, 'restaurant_id' => 20, 'table_id' => 57, 'num_of_people' => 2, 'reservation_date' => '2026-07-18', 'reservation_time' => '18:00:00', 'end_time' => '20:00:00', 'status' => 'pending', 'cancelled_by' => null, 'guest_name' => null, 'phone_number' => null, 'booking_source' => 'online'],
            ['id' => 9, 'user_id' => 2, 'restaurant_id' => 20, 'table_id' => 58, 'num_of_people' => 4, 'reservation_date' => '2026-07-18', 'reservation_time' => '19:30:00', 'end_time' => '21:30:00', 'status' => 'confirmed', 'cancelled_by' => null, 'guest_name' => null, 'phone_number' => null, 'booking_source' => 'online'],
            ['id' => 10, 'user_id' => 2, 'restaurant_id' => 16, 'table_id' => 45, 'num_of_people' => 2, 'reservation_date' => '2026-07-19', 'reservation_time' => '12:00:00', 'end_time' => '14:00:00', 'status' => 'confirmed', 'cancelled_by' => null, 'guest_name' => null, 'phone_number' => null, 'booking_source' => 'online'],
            ['id' => 11, 'user_id' => 8, 'restaurant_id' => 18, 'table_id' => 51, 'num_of_people' => 2, 'reservation_date' => '2026-07-19', 'reservation_time' => '19:00:00', 'end_time' => '20:15:00', 'status' => 'confirmed', 'cancelled_by' => null, 'guest_name' => null, 'phone_number' => null, 'booking_source' => 'online'],
            ['id' => 12, 'user_id' => 2, 'restaurant_id' => 19, 'table_id' => 54, 'num_of_people' => 2, 'reservation_date' => '2026-07-20', 'reservation_time' => '18:30:00', 'end_time' => '19:45:00', 'status' => 'pending', 'cancelled_by' => null, 'guest_name' => null, 'phone_number' => null, 'booking_source' => 'online'],
            ['id' => 13, 'user_id' => null, 'restaurant_id' => 1, 'table_id' => 3, 'num_of_people' => 5, 'reservation_date' => '2026-07-18', 'reservation_time' => '18:00:00', 'end_time' => '20:00:00', 'status' => 'confirmed', 'cancelled_by' => null, 'guest_name' => 'Alex Morgan', 'phone_number' => '09011112222', 'booking_source' => 'manual'],
            ['id' => 14, 'user_id' => null, 'restaurant_id' => 2, 'table_id' => 6, 'num_of_people' => 3, 'reservation_date' => '2026-07-18', 'reservation_time' => '18:15:00', 'end_time' => '19:45:00', 'status' => 'confirmed', 'cancelled_by' => null, 'guest_name' => 'Walk-in Guest', 'phone_number' => null, 'booking_source' => 'walk_in'],
        ];

        foreach ($reservations as $reservation) {
            DB::table('reservations')->insert(array_merge($reservation, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
