<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'table_name',
        'capacity', 
        'is_active',
    ];

    /**
     * リレーション：この座席はどのレストランのものか
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * リレーション：この座席に紐づく予約オブジェクト一覧を取得
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}