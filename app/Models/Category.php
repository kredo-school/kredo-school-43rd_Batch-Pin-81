<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_name',
    ];

    public function restaurants()
    {
        return $this->belongsToMany(
            Restaurant::class,
            'category_restaurant'
        );
    }
}
