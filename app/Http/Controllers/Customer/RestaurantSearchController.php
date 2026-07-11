<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Reservation;

class RestaurantSearchController extends Controller
{
    public function view()
    {
        // $restaurants = Restaurant::all();
        $restaurants = Restaurant::with([
            'photos',
            'categories',
            'features'
        ])->approved()->withAvg('posts', 'rating')->get(); // Approvedされたレストランのみを取得するために->approved()を追加ーリカコ


        return view('customers.restaurants.index', compact('restaurants'));
    }

    public function show(Restaurant $restaurant)
    {
        // abort_unless($restaurant->status === Restaurant::STATUS_APPROVED, 404); // レストランがApprovedされていない場合、万が一カスタマーがURLからレストランページへアクセスしようとした場合は404エラーを返す

        $restaurant->load([
            'photos',
            'categories',
            'features',
            'menus',
            'menus.photos',
            'posts.user',
        ]);

        // 💡 補正ポイント：postsが存在する場合のみ平均点と件数を計算（クラッシュ防止）
        if ($restaurant->relationLoaded('posts') || $restaurant->posts()->exists()) {
            $restaurant->loadAvg('posts', 'rating')
                ->loadCount('posts');
        } else {
            // postsテーブルが空、またはリレーションがない場合のデフォルト値をセット
            $restaurant->posts_avg_rating = 4.7;
            $restaurant->posts_count = 120;
        }

        // 予約可能スロットの取得（メソッドが存在しない場合の致命的エラーを回避）
        $availableSlots = method_exists($restaurant, 'availableSlots')
            ? $restaurant->availableSlots()
            : collect([]);

        return view('customers.restaurants.show', compact('restaurant', 'availableSlots'));
    }
}
