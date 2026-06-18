<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function myPage()
    {
        $user = Auth::user();

        return view('customer.my_page', compact('user'));
    }
    public function index(Request $request)
    {
        // 💡 余計なエラーをすべて回避し、ただフロント画面を呼び出すだけにします！
        return view('customer.reviews.index');

    // public function index(Request $request)
    // {
    //     $user = Auth::user();
    //     // タブの切り替え判定（デフォルトは 'following'）
    //     $tab = $request->query('tab', 'following');

    //     // クエリの基本形（リレーション先のユーザーとレストランを効率よく一緒に取得）
    //     $query = Review::with(['user', 'restaurant']);

    //     // ─── タブによる切り替え ───
    //     if ($tab === 'following') {
    //         $followingIds = $user->followings()->pluck('following_id');
    //         $query->whereIn('user_id', $followingIds);
    //     } else {
    //         $query->where('user_id', '!=', $user->id);
    //     }

    //     // ─── フィルタードロワーからの絞り込み処理 ───
    //     if ($request->filled('category')) {
    //         $query->whereHas('restaurant', function($q) use ($request) {
    //             $q->where('category', $request->category);
    //         });
    //     }

    //     if ($request->filled('rating')) {
    //         $query->where('rating', '>=', $request->rating);
    //     }

    //     if ($request->filled('area')) {
    //         $query->whereHas('restaurant', function($q) use ($request) {
    //             $q->where('area', 'LIKE', '%' . $request->area . '%');
    //         });
    //     }

    //     if ($request->boolean('english_only')) {
    //         $query->whereHas('restaurant', function($q) {
    //             $q->where('is_english_available', true);
    //         });
    //     }

    //     $reviews = $query->latest()->get();

    //     return view('customer.reviews.index', compact('reviews', 'tab'));
    // }
    // public function toggleFollow(User $user)
    // {
    //     $me = Auth::user();
        
    //     if ($me->id === $user->id) {
    //         return redirect()->back()->with('error', 'You cannot follow yourself.');
    //     }
    //     $me->followings()->toggle($user->id);

    //     return redirect()->back();
    // }
    // public function userProfile(User $user)
    // {
    //     // 該当ユーザーの口コミ一覧も一緒に取得してプロフィール画面に渡す
    //     $reviews = Review::with('restaurant')->where('user_id', $user->id)->latest()->get();
    //     return view('customer.user_profile', compact('user', 'reviews'));
    // }
}
}