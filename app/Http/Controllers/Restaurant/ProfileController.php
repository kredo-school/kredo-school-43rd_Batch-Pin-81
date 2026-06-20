<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant; // 必要に応じて実際のモデル名に変更してください
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面の表示
     */
    public function edit()
    {
        // 本来はログインしている店舗ユーザーの情報を取得します
        // $restaurant = Auth::user()->restaurant; 

        // フォームの初期表示用サンプルデータ
        $restaurant = (object)[
            'name' => 'Sushi Masaru',
            'description' => 'Traditional Edomae sushi in contemporary setting',
            'cuisine_types' => ['Sushi'],
            'address' => '3-8-15 Ginza, Chuo-ku, Tokyo',
            'phone' => '+81-3-1234-5678',
            'email' => 'info@sushimasaru.jp',
            'website' => 'www.sushimasaru.jp',
            'instagram' => 'https://instagram.com/yourrestaurant',
            'facebook' => 'https://facebook.com/yourrestaurant',
            'twitter' => 'https://x.com/yourrestaurant',
            'capacity' => 8,
            'features' => [
                'english_menu' => true,
                'credit_cards' => true,
                'reservations_required' => true,
                'english_speaking_staff' => true,
                'vegetarian_options' => false,
                'halal_options' => false,
            ],
            'hours' => [
                'Monday' => ['open' => '17:00', 'close' => '22:00', 'closed' => false],
                'Tuesday' => ['open' => '17:00', 'close' => '22:00', 'closed' => false],
                'Wednesday' => ['open' => '17:00', 'close' => '22:00', 'closed' => false],
                'Thursday' => ['open' => '17:00', 'close' => '22:00', 'closed' => false],
                'Friday' => ['open' => '17:00', 'close' => '22:00', 'closed' => false],
                'Saturday' => ['open' => '17:00', 'close' => '22:00', 'closed' => false],
                'Sunday' => ['open' => '', 'close' => '', 'closed' => true],
            ]
        ];

        return view('restaurants.profile', compact('restaurant'));
    }

    /**
     * プロフィール更新処理
     */
    public function update(Request $request)
    {
        return redirect()->route('restaurant.profile.edit')->with('success', 'Changes saved successfully.');
    }
}
