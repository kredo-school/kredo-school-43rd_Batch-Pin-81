<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        // 1. 人気エリアのデータ（Food Categoriesなどでお使いのものです）
        $chartData = [
            ['name' => 'Ginza', 'count' => '234 restaurants', 'desc' => 'Upscale dining & luxury'],
            ['name' => 'Shibuya', 'count' => '189 restaurants', 'desc' => 'Trendy & vibrant'],
            ['name' => 'Shinjuku', 'count' => '267 restaurants', 'desc' => 'Izakaya & nightlife'],
            ['name' => 'Roppongi', 'count' => '156 restaurants', 'desc' => 'International cuisine']
        ];

        // 2. 本物の店舗データをDBから取得
        $all_restaurants = Restaurant::all();

        // 💡 補正ポイント: もしDBが空の場合の保険（マップがエラーを吐かないようダミーを詰める）
        if ($all_restaurants->isEmpty()) {
            $all_restaurants = collect([
                (object)[
                    'id' => 1,
                    'restaurant_name' => 'Sushi Masaru',
                    'city' => 'Ginza, Tokyo',
                    'latitude' => 35.6724,
                    'longitude' => 139.7649,
                    'rating' => '4.8',
                    'reviews' => '245',
                    'type' => 'Sushi',
                    'tags' => ['English Menu', 'Available Now']
                ],
                (object)[
                    'id' => 2,
                    'restaurant_name' => 'Ramen Ichiban',
                    'city' => 'Shibuya, Tokyo',
                    'latitude' => 35.6580,
                    'longitude' => 139.7016,
                    'rating' => '4.6',
                    'reviews' => '892',
                    'type' => 'Ramen',
                    'tags' => ['Walk-ins Welcome', 'English Menu']
                ],
                (object)[
                    'id' => 3,
                    'restaurant_name' => 'Yakitori Tori',
                    'city' => 'Shinjuku, Tokyo',
                    'latitude' => 35.6938,
                    'longitude' => 139.7034,
                    'rating' => '4.7',
                    'reviews' => '421',
                    'type' => 'Yakitori',
                    'tags' => ['Available Now', 'English Speaking']
                ]
            ]);
        }

        // Bladeで使われなくなった古い配列 $restaurantData は綺麗に削除しました。
        return view('customer.index', compact('chartData', 'all_restaurants'));
    }

    public function search()
    {
        return view('customer.search');
    }
}