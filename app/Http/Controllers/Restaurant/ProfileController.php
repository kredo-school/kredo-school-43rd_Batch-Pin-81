<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Feature;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面の表示
     */
    public function edit()
    {
        $restaurant = Auth::user()->restaurant; 

        if (!$restaurant) {
            $restaurant = new Restaurant();
            $restaurant->user_id = Auth::id();
        }
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
        $restaurant = Auth::user()->restaurant;

        if (!$restaurant) {
            $restaurant = new Restaurant();  
            $restaurant->user_id = Auth::id();
        }

        // バリデーション
        $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'description'     => 'nullable|string',
            'address'         => 'nullable|string|max:255',
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
        ]);

        // 基本情報の保存
        $restaurant->restaurant_name = $request->restaurant_name;
        $restaurant->description     = $request->description;
        $restaurant->address         = $request->address;
        $restaurant->phone_number    = $request->phone_number;
        $restaurant->website         = $request->website;
        $restaurant->instagram       = $request->instagram;
        $restaurant->facebook        = $request->facebook;
        $restaurant->twitter         = $request->twitter;
        $restaurant->stay_duration   = $request->stay_duration;
        $restaurant->capacity        = $request->capacity;
        $restaurant->operating_hours = $request->input('hours', []);
        $restaurant->save();

        // Categoryの同期処理（syncにより、外されたチェックは自動消去）
        $categoryIds = $request->input('cuisine_types', []);
        $restaurant->categories()->sync($categoryIds);

        // 🔗 Featureの同期処理（syncにより、外されたチェックは自動消去）
        $featureIds = $request->input('features', []);
        $restaurant->features()->sync($featureIds);

        return redirect()->route('restaurant.profile.edit');
    }
}