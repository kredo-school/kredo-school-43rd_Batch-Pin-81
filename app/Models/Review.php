<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // 以前あなたが作成した大切な設定（そのまま残します）
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'rating',
        'comment',
        'image_path',
    ];

    // 1. 投稿したユーザーの情報（以前あなたが作成したもの：これ1つだけに絞ります）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 2. レビューがどこのお店のものか（今回必要なもの）
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // 3. レビューに紐づく画像（今回必要なもの）
    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }
}
