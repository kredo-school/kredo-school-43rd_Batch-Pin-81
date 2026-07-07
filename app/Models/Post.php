<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Comment;
use App\Models\Star;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'description',
        'image',
        'user_id',
        'restaurant_id',
        'rating',
    ];

    public function user()
    {
        return $this->belongTo(User::class);
    }
    public function restaurant()
    {
        return $this->belongTo(Restaurant::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function star()
    {
        return $this->hasOne(Star::class);
    }
}
