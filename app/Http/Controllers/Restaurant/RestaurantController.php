<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{

    public function create()
    {

        return view('auth.restaurant_register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'restaurant_name' => 'required|max:255',
            'postal_code' => 'required|max:20',
            'prefecture' => 'required|max:255',
            'address' => 'required|max:255',
            'phone_number' => 'nullable|max:20',
            'description' => 'nullable',
            'business_license' => 'nullable',
        ]);

        $fullAddress = $validated['postal_code']
            . ' '
            . $validated['prefecture']
            . ' '
            . $validated['address'];

        Restaurant::create([
            'user_id' => Auth::id(),
            'restaurant_name' => $validated['restaurant_name'],
            'address' => $fullAddress,
            'phone_number' => $validated['phone_number'] ?? null,
            'description' => $validated['description'] ?? null,
            'business_license' => $validated['business_license'] ?? null,
            'status' => Restaurant::STATUS_PENDING,
        ]);

        return redirect('/restaurant/dashboard')
            ->with('success', 'Restaurant registered successfully.');
    }
    public function reviews()
{
    // 1. 現在ログインしている店舗（ユーザー）に紐づくレストラン情報を取得
    $restaurant = Restaurant::where('user_id', Auth::id())->first();

    if (!$restaurant) {
        return redirect()->back()->with('error', 'レストラン情報が見つかりません。');
    }

    // 2.すべての投稿データが保存されているPostモデルから、この店舗（restaurant_id）宛ての口コミだけを絞り込んで取得
    $reviews = Post::with(['user', 'star', 'comments.user'])
                   ->where('restaurant_id', $restaurant->id)
                   ->latest()
                   ->get();

    // 3. 統計データ（星の平均など、先ほどのBladeで必要になる配列）を作成
    $stats = [
        'average_rating' => $reviews->avg('star.rating') ?? 0,
        'total_reviews'  => $reviews->count(),
        'reported_count' => 0,
        'stars' => [
            5 => ['percentage' => 0, 'count' => 0],
            4 => ['percentage' => 0, 'count' => 0],
            3 => ['percentage' => 0, 'count' => 0],
            2 => ['percentage' => 0, 'count' => 0],
            1 => ['percentage' => 0, 'count' => 0],
        ]
    ];

    // 店舗用のレビューBlade（restaurants/reviews.blade.php）にデータを渡して表示
    return view('restaurants.reviews', compact('reviews', 'stats'));
}
}
