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
    public function profile()
    {
        return view('customer.profile');
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
        ]);

        // 2. 実際の保存処理（例：ログイン中ユーザーのデータを上書き）
        // $user = Auth::user();
        // $user->update($request->all());

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }
    public function destroy()
    {
        // 1. 退会処理（データを消す、または無効化する）
        // $user = Auth::user();
        // $user->delete();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }
}
