<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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
        'postal_code',
        'prefecture',
        'city',
        'street_address_building',
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
        'operating_hours' => 'array',
    ];

    public function availableSlots()
    {
        $slots = [];

        $start = Carbon::parse($this->open_time);
        $end = Carbon::parse($this->close_time);

        while ($start < $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes(15);
        }

        return $slots;
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_restaurant'
        );
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function menus()
    {
        return $this->hasMany(Reservation::class);
    }


    public function features()
    {
        return $this->belongsToMany(
            Feature::class,
            'feature_restaurant'
        );
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

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
