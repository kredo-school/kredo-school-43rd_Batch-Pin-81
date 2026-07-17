<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Feature;
use App\Services\ReservationAvailabilityService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(
        private readonly ReservationAvailabilityService $availability
    ) {}

    /**
     * プロフィール編集画面の表示
     */
    public function edit()
    {
        $restaurant = $this->currentRestaurant();

        // DBのマスターテーブルから選択肢をすべて取得
        $allCategories = Category::all();
        $allFeatures   = Feature::all();

        // 中間テーブルから、現在このお店が選択しているIDを配列形式で抽出
        $selectedCategoryIds = $restaurant->categories()->pluck('categories.id')->toArray();
        $selectedFeatureIds  = $restaurant->features()->pluck('features.id')->toArray();

        return view('restaurants.profile', compact(
            'restaurant',
            'allCategories',
            'allFeatures',
            'selectedCategoryIds',
            'selectedFeatureIds'
        ));
    }

    /**
     * プロフィール更新処理
     */
    public function update(Request $request)
    {
        $restaurant = $this->currentRestaurant();

        // バリデーション
        $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'description'     => 'nullable|string',
            // 'address'         => 'nullable|string|max:255',
            'phone_number'    => 'nullable|string|max:50',
            'website'         => 'nullable|string|max:255',
            'instagram'       => 'nullable|string|max:255',
            'facebook'        => 'nullable|string|max:255',
            'twitter'         => 'nullable|string|max:255',
            'stay_duration'   => 'nullable|integer',
            'capacity'        => 'nullable|integer|min:0',
            'cuisine_types'   => 'nullable|array',
            'features'        => 'nullable|array',
            'hours'           => 'nullable|array',
            'postal_code' => 'required|string|max:255',
            'prefecture' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'street_address_building' => 'required|string|max:255',
        ]);

        // 基本情報の保存
        $restaurant->restaurant_name = $request->restaurant_name;
        $restaurant->description     = $request->description;
        // $restaurant->address         = $request->address;
        $restaurant->phone_number    = $request->phone_number;
        $restaurant->website         = $request->website;
        $restaurant->instagram       = $request->instagram;
        $restaurant->facebook        = $request->facebook;
        $restaurant->twitter         = $request->twitter;
        $restaurant->stay_duration   = $request->stay_duration;
        $restaurant->capacity        = $request->capacity;
        $restaurant->operating_hours = $this->availability
            ->buildOperatingHoursFromRequest(
                $request->input('hours', []),
                $restaurant->operating_hours ?? []
            );
        $restaurant->postal_code = $request->postal_code;
        $restaurant->prefecture = $request->prefecture;
        $restaurant->city = $request->city;
        $restaurant->street_address_building = $request->street_address_building;
        $restaurant->save();

        // Categoryの同期処理（syncにより、外されたチェックは自動消去）
        $categoryIds = $request->input('cuisine_types', []);
        $restaurant->categories()->sync($categoryIds);

        // 🔗 Featureの同期処理（syncにより、外されたチェックは自動消去）
        $featureIds = $request->input('features', []);
        $restaurant->features()->sync($featureIds);

        return redirect()->route('restaurant.profile.edit');
    }

    private function currentRestaurant(): Restaurant
    {
        $restaurant = Restaurant::where('user_id', Auth::id())->first();

        if ($restaurant) {
            return $restaurant;
        }

        $fallbackRestaurant = Restaurant::first();

        if (!$fallbackRestaurant) {
            $fallbackRestaurant = new Restaurant();
            $fallbackRestaurant->user_id = Auth::id();
        }

        return $fallbackRestaurant;
    }
}
