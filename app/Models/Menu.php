<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;
use App\Models\Photo;

class Menu extends Model
{
    protected $fillable = [
        'restaurant_id',
        'menu_name',
        'price',
        'menu_category',
        'description',
        'image_path',
        'menu_image',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
