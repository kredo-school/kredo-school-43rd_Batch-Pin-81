<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_SUSPENDED = 'suspended';

    protected static function booted()
    {
        static::creating(function ($restaurant) {
            $restaurant->status = self::STATUS_PENDING;
        });
    }

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
        'status',
    ];

    // 配列やJSON形式のオブジェクトデータを自動変換する設定
    protected $casts = [
        'cuisine_types' => 'array',
        'operating_hours' => 'array',
        'features' => 'array',
    ];
    public function getHoursAttribute()
    {
        return $this->operating_hours ?? [];
    }

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

    public function getOwnerNameAttribute()
    {
        return $this->user
            ? $this->user->first_name . ' ' . $this->user->last_name
            : '-';
    }

    /**
     * レストランが持つカテゴリー（料理ジャンル）
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_restaurant', 'restaurant_id', 'category_id');
    }

    /**
     * レストランが持つ特徴
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_restaurant', 'restaurant_id', 'feature_id');
    }
}
