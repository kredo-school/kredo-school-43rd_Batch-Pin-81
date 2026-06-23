<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * カスタマー マイページ表示
     */
    public function myPage()
    {
        // 1. ログインしているユーザー情報を取得
        $user = Auth::user();

        // 2. ログインユーザーの投稿一覧（紐づくコメント、ユーザー、評価データも同時に）を取得
        $posts = Post::with(['comments.user', 'user', 'star']) // 星評価リレーションがある場合
                     ->where('user_id', $user->id)
                     ->latest()
                     ->get();

        // 3. 更生した my_page.blade.php にすべてのデータを届ける
        return view('customer.my_page', compact('user', 'posts'));
    }

    /**
     * レビュー一覧表示
     */
    public function index(Request $request)
    {
        // 💡 フロント画面をシンプルに呼び出す設定を維持します
        return view('customer.reviews.index');
    }

    // 💡 以下、今後使う可能性があるためコメントアウトのまま綺麗に保持します
    /*
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'following');
        $query = Review::with(['user', 'restaurant']);

        if ($tab === 'following') {
            $followingIds = $user->followings()->pluck('following_id');
            $query->whereIn('user_id', $followingIds);
        } else {
            $query->where('user_id', '!=', $user->id);
        }

        if ($request->filled('category')) {
            $query->whereHas('restaurant', function($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        if ($request->filled('area')) {
            $query->whereHas('restaurant', function($q) use ($request) {
                $q->where('area', 'LIKE', '%' . $request->area . '%');
            });
        }

        if ($request->boolean('english_only')) {
            $query->whereHas('restaurant', function($q) {
                $q->where('is_english_available', true);
            });
        }

        $reviews = $query->latest()->get();
        return view('customer.reviews.index', compact('reviews', 'tab'));
    }

    public function toggleFollow(User $user)
    {
        $me = Auth::user();
        if ($me->id === $user->id) {
            return redirect()->back()->with('error', 'You cannot follow yourself.');
        }
        $me->followings()->toggle($user->id);
        return redirect()->back();
    }

    public function userProfile(User $user)
    {
        $reviews = Review::with('restaurant')->where('user_id', $user->id)->latest()->get();
        return view('customer.user_profile', compact('user', 'reviews'));
    }
    */
}