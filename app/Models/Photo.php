<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'menu_id',
        'photo_path',
        'photo_category'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menu(){
        return $this->belongsTo(Menu::class);
    }
}
