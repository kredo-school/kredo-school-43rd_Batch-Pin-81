<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
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
        'latitude',
        'longitude',
        'status',
    ];

    // 配列やJSON形式のオブジェクトデータを自動変換する設定
    protected $casts = [
        'cuisine_types' => 'array',
        'operating_hours' => 'array',
        'hours'           => 'array',
        'features' => 'array',
    ];

    public function getHoursAttribute()
    {
        return $this->operating_hours ?? [];
    }

    public function getOpenTimeAttribute(): ?string
    {
        if (!empty($this->attributes['open_time'] ?? null)) {
            return $this->attributes['open_time'];
        }

        $hours = $this->hours;
        $earliest = null;

        foreach ($hours as $daySlots) {
            foreach ($daySlots as $slot) {
                if (!isset($slot['open'])) {
                    continue;
                }

                if ($earliest === null || $slot['open'] < $earliest) {
                    $earliest = $slot['open'];
                }
            }
        }

        return $earliest;
    }

    public function getCloseTimeAttribute(): ?string
    {
        if (!empty($this->attributes['close_time'] ?? null)) {
            return $this->attributes['close_time'];
        }

        $hours = $this->hours;
        $latest = null;

        foreach ($hours as $daySlots) {
            foreach ($daySlots as $slot) {
                if (!isset($slot['close'])) {
                    continue;
                }

                if ($latest === null || $slot['close'] > $latest) {
                    $latest = $slot['close'];
                }
            }
        }

        return $latest;
    }

    public function getTodayHoursAttribute(): ?string
    {
        $todayKey = strtolower(now()->format('l'));
        $dayHours = $this->hours[$todayKey] ?? [];

        if (empty($dayHours)) {
            return null;
        }

        if (isset($dayHours['closed']) && $dayHours['closed']) {
            return 'Closed today';
        }

        $slots = [];

        foreach ($dayHours as $slot) {
            if (empty($slot['open']) || empty($slot['close'])) {
                continue;
            }

            $slots[] = $slot['open'] . ' - ' . $slot['close'];
        }

        return $slots ? implode(' / ', $slots) : null;
    }

    public function availableSlots()
    {
        $slots = [];

        $startTime = $this->open_time;
        $endTime = $this->close_time;

        if (!$startTime || !$endTime) {
            return $slots;
        }

        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        while ($start < $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes(15);
        }

        return $slots;
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    // reservation::をmenuに変えた
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * リレーション：このレストランに入っている予約オブジェクト一覧を取得
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
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

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
