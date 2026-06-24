<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_name',
        'address',
        'phone_number',
        'description',
        'business_license',
    ];
}
