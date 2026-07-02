<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    const ROLE_USER = 1;
    const ROLE_RESTAURANT = 2;
    const ROLE_ADMIN = 3;

    public function isAdmin()
    {
        return $this->role_id == self::ROLE_ADMIN;
    }

    public function isRestaurant()
    {
        return $this->role_id == self::ROLE_RESTAURANT;
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return trim(sprintf('%s %s', $this->first_name ?? '', $this->last_name ?? ''));
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->first_name ?: 'User') . '&color=7F9CF5&background=EBF4FF';
    }
    public function restaurant()
    {
        return $this->hasOne(Restaurant::class);
    }
}
