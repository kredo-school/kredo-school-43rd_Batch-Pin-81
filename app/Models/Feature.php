<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_name',
    ];

    public function restaurants()
    {
        return $this->belongsToMany(
            Restaurant::class,
            'feature_restaurant'
        );
    }
}
