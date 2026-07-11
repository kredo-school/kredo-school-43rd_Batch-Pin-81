<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;

class Menu extends Model
{
    protected $fillable = ['restaurant_id', 'menu_name', 'price', 'menu_category', 'description'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

}
