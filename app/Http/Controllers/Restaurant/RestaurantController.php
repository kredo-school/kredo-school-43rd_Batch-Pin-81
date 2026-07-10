<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewRestaurantApplication;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{

    public function create()
    {
        return view('auth.restaurant_register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_name' => 'required|max:255',
            'postal_code' => 'required|max:20',
            'prefecture' => 'required|max:255',
            'city' => 'required|max:20',
            'street_address_building' => 'required|string|max:255',
            'phone_number' => 'nullable|max:20',
            'description' => 'nullable',
            'business_license' => 'required|file|mimes:pdf|max:5120',
        ]);

        $licensePath = $request->file('business_license')
            ->store('business_licenses', 'public');

        $restaurant = Restaurant::create([
            'user_id' => Auth::id(),
            'restaurant_name' => $validated['restaurant_name'],
            'postal_code' => $validated['postal_code'],
            'prefecture' => $validated['prefecture'],
            'city' => $validated['city'],
            'street_address_building' => $validated['street_address_building'],
            'phone_number' => $validated['phone_number'] ?? null,
            'description' => $validated['description'] ?? null,
            'business_license' => $licensePath,
            'status' => Restaurant::STATUS_PENDING,
        ]);

        $admins = User::where('role_id', User::ROLE_ADMIN)->get();

        Notification::send($admins, new NewRestaurantApplication($restaurant));


        // THIS WORKED:

        // use App\Models\Restaurant;
        // use App\Models\User;
        // use App\Notifications\NewRestaurantApplication;
        // use Illuminate\Support\Facades\Notification;

        // $restaurant = Restaurant::create([
        //     'user_id' => 15,
        //     'restaurant_name' => 'name',
        //     'postal_code' => '123-45667',
        //     'prefecture' => 'Tokyo',
        //     'city' => 'Shibuya',
        //     'street_address_building' => '1-2-3 shibuya, shibuya building',
        //     'phone_number' => '090-1234-5678',
        //     'description' => 'hello world',
        //     'business_license' => 'askdjfbwoiueh',
        //     'status' => Restaurant::STATUS_PENDING,
        // ]);

        // $admins = User::where('role_id', User::ROLE_ADMIN)->get();

        // Notification::send($admins, new NewRestaurantApplication($restaurant));

        return redirect()
            ->route('restaurant.thankyou')
            ->with('success', 'Your application has been submitted.');
    }

    
    public function update(Request $request, Restaurant $restaurant)
    {
        $restaurant->update([
            'operating_hours' => $request->hours,
        ]);

        return redirect()->back()->with('success', 'Restaurant updated.');
    }

    public function reviews()
    {
        // 1. 現在ログインしている店舗（ユーザー）に紐づくレストラン情報を取得
        $restaurant = Restaurant::where('user_id', Auth::id())->first();

        if (!$restaurant) {
            return redirect()->back()->with('error', 'レストラン情報が見つかりません。');
        }

        // 2.すべての投稿データが保存されているPostモデルから、この店舗（restaurant_id）宛ての口コミだけを絞り込んで取得
        $reviews = Post::with(['user', 'comments.user']) // removed 'star'
            ->where('restaurant_id', $restaurant->id)
            ->latest()
            ->get();

        // 3. 統計データ（星の平均など、先ほどのBladeで必要になる配列）を作成
        $stats = [
            // 'average_rating' => $reviews->avg('stats.rating') ?? 0,
            'average_rating' => $restaurant->posts()->avg('rating') ?? 0,
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

        $stats['percentage'] = ($stats['average_rating'] / 5) * 100;

        foreach ([1, 2, 3, 4, 5] as $star) {
            $count = $reviews->where('rating', $star)->count();

            $stats['stars'][$star]['count'] = $count;

            $stats['stars'][$star]['percentage'] =
                $stats['total_reviews']
                ? round(($count / $stats['total_reviews']) * 100)
                : 0;
        }

        // 店舗用のレビューBlade（restaurants/reviews.blade.php）にデータを渡して表示
        return view('restaurants.reviews', compact('reviews', 'stats'));
    }
}
