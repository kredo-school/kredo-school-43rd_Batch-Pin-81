<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Reservation;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * 👤 Display Customer My Page
     */
    public function myPage()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }
        // Get unique restaurants visited within the last 7 days
        $todayStr = Carbon::now()->toDateString();
        $oneWeekAgoStr = Carbon::now()->subDays(7)->toDateString();

        $visitedRestaurants = Reservation::where('user_id', $user->id)
            ->where('status', 'Visited')
            //->whereDate('reservation_date', '>=', $oneWeekAgoStr) TEST時のみ無効化
            ->whereDate('reservation_date', '<=', $todayStr)
            ->with('restaurant')
            ->get()
            ->map(function ($reservation) {
                return $reservation->restaurant;
            })
            ->filter()
            ->unique('id');

        // Eager load related comments, user, and likes for optimization
        $posts = Post::with(['comments.user', 'user', 'likes'])
            ->withCount('likes')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $followers = method_exists($user, 'followers') ? $user->followers : collect([]);
        $followings = method_exists($user, 'followings') ? $user->followings : collect([]);

        return view('customer.my_page', compact('user', 'posts', 'visitedRestaurants', 'followers', 'followings'));
    }

    /**
     * 💬 Display Restaurant Reviews List
     */
    public function showRestaurantReviews($restaurant_id)
    {
        // Fetch posts directly with rating column stored in posts table
        $realReviews = Post::with(['user', 'comments.user'])
            ->where('restaurant_id', $restaurant_id)
            ->latest()
            ->get();

        $totalCount = $realReviews->count();

        // Default values for initial state (if no reviews exist yet)
        $averageRating = $totalCount > 0 ? round($realReviews->avg('rating'), 1) : 4.8;
        $starsData = [
            5 => ['count' => 172, 'percentage' => 70],
            4 => ['count' => 49,  'percentage' => 20],
            3 => ['count' => 12,  'percentage' => 5],
            2 => ['count' => 12,  'percentage' => 5],
            1 => ['count' => 12,  'percentage' => 5],
        ];

        // Recalculate if there are real operational reviews
        if ($totalCount > 0) {
            $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
            foreach ($realReviews as $r) {
                $rating = $r->rating ?? 5;
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
            $statsTotalReviews = $totalCount;
            $reviewCollection = $realReviews;
        } else {
            $statsTotalReviews = 0;
            $reviewCollection = collect([]);
        }

        $reportedCount = Post::where('restaurant_id', $restaurant_id)
            ->where('is_reported', true)
            ->count();

        $stats = [
            'average_rating' => $averageRating,
            'total_reviews'  => $statsTotalReviews,
            'stars'          => $starsData,
            'reported_count' => $reportedCount
        ];

        // Paginating combined collection safely
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $reviewCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $reviews = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $reviewCollection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('restaurants.reviews', compact('reviews', 'stats'));
    }

    /**
     * 📥 Store a New Review Post
     */
    public function store(Request $request, $restaurant_id = null)
    {
        // 1. Strictly validate the incoming parameters (including the live rating)
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'rating'        => 'required|integer|between:1,5',
            'comment'   => 'required|string|max:1000',
            'media'         => 'nullable|array',
            'media.*'       => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,ogg,qt|max:51200',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $finalRestaurantId = $request->input('restaurant_id', $restaurant_id);
        $mediaPaths = [];

        // 2. Handle multiple media attachments uploads safely
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                if ($file->isValid()) {
                    $extension = strtolower($file->getClientOriginalExtension());
                    $folder = in_array($extension, ['mp4', 'mov', 'ogg', 'qt']) ? 'posts/videos' : 'posts/images';
                    $path = $file->store($folder, 'public');
                    $mediaPaths[] = 'storage/' . $path;
                }
            }
        }

        $finalMediaPath = !empty($mediaPaths) ? implode(',', $mediaPaths) : null;

        // 3. Save directly to the Database
        Post::create([
            'user_id'       => $user->id,
            'restaurant_id' => $finalRestaurantId,
            'rating'        => $request->rating,
            'description'   => $request->comment,
            'image'         => $finalMediaPath,
            'status'        => 'visible',
        ]);

        return redirect('/restaurant/' . $finalRestaurantId . '/reviews')
            ->with('success', 'Review posted successfully!');
    }

    /**
     * 🔍 General Routing Stubs
     */
    public function index(Request $request)
    {
        $restaurants = Restaurant::approved()->get(); // Approvedされたレストランのみを取得するために->approved()を追加 : リカコ
        return view('customers.restaurants.index', compact('restaurants'));
    }

    /**
     * 📝 Update an existing description
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'description' => 'required|string|max:5000',
        ]);

        $post->update([
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Post updated successfully.');
    }

    /**
     * 🗑️ Delete Post
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $post->delete();

        return redirect()->route('customer.my_page')->with('success', 'Post deleted successfully.');
    }

    /**
     * 🚨 Report Malicious Content
     */
    public function report(Post $post)
    {
        try {
            $post->is_reported = true;
            $post->save();

            return response()->json([
                'success' => true,
                'message' => 'Reported successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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