<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'user_id',
        'restaurant_name', 
        'description',
        'address',
        'phone_number',    
        'business_license',
        'website',
        'instagram',
        'facebook',
        'twitter',
        'capacity',
        'stay_duration',    
        'operating_hours',  
    ];

    // 配列やJSON形式のオブジェクトデータを自動変換する設定
    protected $casts = [
        'operating_hours' => 'array',
    ];

    /**
     * リレーション：このレストランに入っている予約オブジェクト一覧を取得
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * リレーション：このレストランを所有しているユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}