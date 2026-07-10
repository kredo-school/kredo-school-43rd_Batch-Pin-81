<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'parent_id',
        'title',
        'message',
        'status',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function parent()
    {
        return $this->belongsTo(Contact::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Contact::class, 'parent_id')->oldest();
    }

    public function getSenderLabelAttribute(): string
    {
        if ($this->user && $this->user->isAdmin()) {
            return 'Admin';
        }

        if ($this->restaurant_id || ($this->user && $this->user->isRestaurant())) {
            return 'Restaurant';
        }

        return 'Customer';
    }

    public function getInquiryTypeAttribute(): string
    {
        return $this->restaurant_id ? 'Restaurant' : 'Customer';
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->user ? $this->user->isAdmin() : false;
    }
}
