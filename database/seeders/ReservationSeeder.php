<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        // 既存の予約データを一度クリア
        DB::table('reservations')->delete();

        $reservations = [
            ['id' => 1, 'user_id' => 15, 'restaurant_id' => 1, 'num_of_people' => 2, 'reservation_date' => '2026-07-06', 'reservation_time' => '12:00:00'],
            ['id' => 2, 'user_id' => 15, 'restaurant_id' => 2, 'num_of_people' => 4, 'reservation_date' => '2026-07-07', 'reservation_time' => '19:00:00'],
            ['id' => 3, 'user_id' => 15, 'restaurant_id' => 3, 'num_of_people' => 3, 'reservation_date' => '2026-07-08', 'reservation_time' => '18:30:00'],
            
            ['id' => 4, 'user_id' => 17, 'restaurant_id' => 1, 'num_of_people' => 2, 'reservation_date' => '2026-07-07', 'reservation_time' => '12:00:00'],
            ['id' => 5, 'user_id' => 17, 'restaurant_id' => 2, 'num_of_people' => 4, 'reservation_date' => '2026-07-07', 'reservation_time' => '19:00:00'],
            ['id' => 6, 'user_id' => 17, 'restaurant_id' => 3, 'num_of_people' => 3, 'reservation_date' => '2026-07-08', 'reservation_time' => '18:30:00'],
            
            ['id' => 7, 'user_id' => 18, 'restaurant_id' => 1, 'num_of_people' => 2, 'reservation_date' => '2026-07-06', 'reservation_time' => '12:00:00'],
            ['id' => 8, 'user_id' => 18, 'restaurant_id' => 2, 'num_of_people' => 4, 'reservation_date' => '2026-07-07', 'reservation_time' => '19:00:00'],
            ['id' => 9, 'user_id' => 18, 'restaurant_id' => 3, 'num_of_people' => 3, 'reservation_date' => '2026-07-08', 'reservation_time' => '18:30:00'],
        ];

        foreach ($reservations as $res) {
            DB::table('reservations')->insert([
                'id' => $res['id'],
                'user_id' => $res['user_id'],
                'restaurant_id' => $res['restaurant_id'],
                'table_id' => null,
                'num_of_people' => $res['num_of_people'],
                'reservation_date' => $res['reservation_date'],
                'reservation_time' => $res['reservation_time'],
                'end_time' => null,
                'status' => 'Visited',
                'cancelled_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}