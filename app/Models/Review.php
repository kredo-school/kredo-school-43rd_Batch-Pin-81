<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'rating',
        'comment',
        'image_path',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 既存の restaurant リレーション（24〜27行目あたりにあるもの）
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // 💡 投稿したユーザーの情報（1つだけに絞ります）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 💡 レビューに紐づく画像（複数）
    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }
}
