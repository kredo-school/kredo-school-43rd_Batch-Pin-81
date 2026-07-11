<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    public static function formatReservationCode(int $id): string
    {
        return 'RM' . str_pad((string) $id, 3, '0', STR_PAD_LEFT);
    }

    public function scopeOccupying($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'completed']);
    }

    // データベースへの一括保存を許可するカラム
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'table_id',
        'num_of_people',
        'reservation_date',
        'reservation_time',
        'end_time',
        'reservation_code',
        'status',
        'cancelled_by',
    ];

    protected $casts = [
        'reservation_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
