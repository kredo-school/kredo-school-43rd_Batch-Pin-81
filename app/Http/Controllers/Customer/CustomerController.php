<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        // 1. 人気エリアのダミーデータ
        $chartData = [
            ['name' => 'Ginza', 'count' => '234 restaurants', 'desc' => 'Upscale dining & luxury'],
            ['name' => 'Shibuya', 'count' => '189 restaurants', 'desc' => 'Trendy & vibrant'],
            ['name' => 'Shinjuku', 'count' => '267 restaurants', 'desc' => 'Izakaya & nightlife'],
            ['name' => 'Roppongi', 'count' => '156 restaurants', 'desc' => 'International cuisine']
        ];

        // 2. レストランのダミーデータ
        $restaurantData = [
            ['name' => 'Sushi Masaru', 'type' => 'Sushi', 'rating' => '4.8', 'reviews' => '245', 'loc' => 'Ginza, Tokyo', 'tags' => ['English Menu', 'Available Now']],
            ['name' => 'Ramen Ichiban', 'type' => 'Ramen', 'rating' => '4.6', 'reviews' => '892', 'loc' => 'Shibuya, Tokyo', 'tags' => ['Walk-ins Welcome', 'English Menu']],
            ['name' => 'Yakitori Tori', 'type' => 'Yakitori', 'rating' => '4.7', 'reviews' => '421', 'loc' => 'Shinjuku, Tokyo', 'tags' => ['Available Now', 'English Speaking']]
        ];

        return view('customer.index', compact('chartData', 'restaurantData'));
    }
}
