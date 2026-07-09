<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Models\Reservation;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // 👤 customer/my_page.blade.php用
    public function myPage()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $todayStr = Carbon::now()->toDateString();
        $oneWeekAgoStr = Carbon::now()->subDays(7)->toDateString();
        $visitedRestaurants = Reservation::where('user_id', $user->id)
            ->where('status', 'Visited')
            ->whereBetween('reservation_date', [$oneWeekAgoStr, $todayStr])
            ->with('restaurant')
            ->get()
            ->pluck('restaurant')
            ->filter()
            ->unique('id');

        // 【最適化】likesのリレーションも一緒にEager Loadしておきます
        $posts = Post::with(['comments.user', 'user', 'likes'])
            ->withCount('likes')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $followers = method_exists($user, 'followers') ? $user->followers : collect([]);
        $followings = method_exists($user, 'followings') ? $user->followings : collect([]);

        return view('customer.my_page', compact('user', 'posts', 'visitedRestaurants', 'followers', 'followings'));
    }

    // 💬 レストランの口コミ一覧ページを表示 (restaurant/reviews.blade.php 用)
    public function showRestaurantReviews($restaurant_id)
    {
        $realReviews = Post::with(['user', 'star', 'comments.user'])
            ->where('restaurant_id', $restaurant_id)
            ->latest()
            ->get();

        $totalCount = $realReviews->count();
        $averageRating = $totalCount > 0 ? $realReviews->avg('star.rating') : 4.8;
        $starsData = [
            5 => ['count' => 172, 'percentage' => 70],
            4 => ['count' => 49,  'percentage' => 20],
            3 => ['count' => 12,  'percentage' => 5],
            2 => ['count' => 12,  'percentage' => 5],
            1 => ['count' => 12,  'percentage' => 5],
        ];

        if ($totalCount > 0) {
            $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
            foreach ($realReviews as $r) {
                $rating = optional($r->star)->rating ?? 5;
                if (isset($counts[$rating])) {
                    $counts[$rating]++;
                }
            }
            foreach ($counts as $star => $count) {
                $starsData[$star] = [
                    'count' => $count,
                    'percentage' => round(($count / $totalCount) * 100)
                ];
            }
        }

        $stats = [
            'average_rating' => $averageRating,
            'total_reviews'  => $totalCount > 0 ? $totalCount : 245,
            'stars'          => $starsData,
            'reported_count' => 0
        ];

        if ($totalCount > 0) {
            $reviewCollection = $realReviews;
        } else {
            $dummyReview = (object)[
                'id'          => 1,
                'description' => 'Amazing experience! The chef\'s omakase was incredible. Will definitely come back.',
                'image'       => null,
                'created_at'  => '2026-05-10',
                'is_reported' => false,
                'user' => (object)[
                    'name' => 'John Smith'
                ],
                'star' => (object)[
                    'rating' => 5
                ],
                'comments' => collect([])
            ];
            $reviewCollection = [$dummyReview];
        }

        $reviews = new \Illuminate\Pagination\LengthAwarePaginator(
            $reviewCollection,
            $totalCount > 0 ? $totalCount : 1,
            10,
            10,
            ['path' => request()->url()]
        );

        return view('customers.restaurants.reviews', compact('reviews', 'stats'));
    }

    public function store(Request $request, $restaurant_id = null)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'description'   => 'nullable|string|max:1000',
            'media'         => 'nullable|array',
            'media.*'       => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,ogg,qt|max:51200',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $finalRestaurantId = $request->input('restaurant_id', $restaurant_id);
        $mediaPaths = [];

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                if ($file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $folder = in_array($extension, ['mp4', 'mov', 'ogg', 'qt']) ? 'posts/videos' : 'posts/images';
                    $path = $file->store($folder, 'public');
                    $mediaPaths[] = 'storage/' . $path;
                }
            }
        }

        $finalMediaPath = !empty($mediaPaths) ? implode(',', $mediaPaths) : null;

        $post = Post::create([
            'user_id'       => $user->id,
            'restaurant_id' => $finalRestaurantId,
            'rating'        => $request->rating,
            'description'   => $request->description,
            'image'         => $finalMediaPath,
        ]);

        return redirect()->route('customer.my_page')->with('success', 'Review posted successfully!');
    }

    public function index(Request $request)
    {
        $restaurants = Restaurant::all();
        return view('customers.restaurants.index', compact('restaurants'));
    }

    public function search()
    {
        return view('customer.mypage');
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'description'  => 'required|string|max:5000', 
        ]);

        $post->update([
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        $post->delete();

        return redirect()->route('customer.my_page')->with('success', 'Post deleted successfully.');
    }

    public function report(Post $post)
    {
        if ($post->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot report your own post.');
        }
        
        $post->update([
            'is_reported' => true,
        ]);

        return redirect()->back()->with('success', 'Post has been reported.');
    }
}

/* ==========================================================
         * 🚨 後でTableを作成後に使用するコーディング（現在はコメントアウト）
         * ==========================================================
        // 1. 全レビューの基本統計（平均点、総件数）を取得
        $reviewStats = Review::select(
            DB::raw('COUNT(*) as total_reviews'),
            DB::raw('ROUND(AVG(rating), 1) as average_rating')
        )->first();

        $totalReviews = $reviewStats->total_reviews ?? 0;
        $averageRating = $reviewStats->average_rating ?? 0.0;

        // 2. 星1〜5それぞれの件数を取得してパーセンテージを計算
        $starCounts = Review::select('rating', DB::raw('COUNT(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $stars = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $starCounts[$i] ?? 0;
            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
            
            $stars[$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        // 3. 通報されたレビューの件数を取得
        $reportedCount = Review::where('is_reported', true)->count();

        // 統計データをまとめる
        $stats = [
            'average_rating' => $averageRating,
            'total_reviews' => $totalReviews,
            'stars' => $stars,
            'reported_count' => $reportedCount
        ];

        // 4. レビュー一覧を取得
        $reviews = Review::with(['user', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
         ========================================================== */

// 💡 以下、今後使う可能性があるためコメントアウトのまま綺麗に保持します
    /*
/*customer側のもの
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