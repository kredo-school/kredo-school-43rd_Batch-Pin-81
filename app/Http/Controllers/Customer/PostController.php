<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Restaurant;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // 👤 customer/my_page.blade.php用
    public function myPage()
    {
        $user = Auth::user(); // Auth() -> Auth:: に統一

        if (!$user) {
            return redirect()->route('login');
        }
        
        // 💡 過去1週間以内に「来店済み」になった予約から店舗リストを取得
        // ※ 予約日時（reservation_start）が【今日から1週間前 〜 今現在】の間のものを対象
        $visitedRestaurants = Reservation::where('user_id', $user->id)
            // ->where('status', 'visited') // 💡 もし「来店済み」を表すステータスがあればここのコメントアウトを外してください
            ->whereBetween('reservation_start', [Carbon::now()->subWeek(), Carbon::now()])
            ->with('restaurant')
            ->get()
            ->pluck('restaurant') // 予約データから関連するレストラン情報だけを抽出
            ->filter()            // 念のため空のデータを排除
            ->unique('id');       // 重複する店舗を排除

        // マイページ用に自分が投稿したPostを取得
        $posts = Post::with(['comments.user', 'user', 'star'])
                     ->where('user_id', $user->id)
                     ->latest()
                     ->get();

        // 💡 ビューに $visitedRestaurants を追加して渡す
        return view('customer.my_page', compact('user', 'posts', 'visitedRestaurants'));
    }

    // 💬 レストランの口コミ一覧ページを表示する (restaurant/reviews.blade.php 用)
    public function showRestaurantReviews($restaurant_id)
    {
        // 1. そのレストランに紐づく本物の口コミ（Post）を取得
        $realReviews = Post::with(['user', 'star', 'comments.user'])
                           ->where('restaurant_id', $restaurant_id)
                           ->latest()
                           ->get();

        // 2. 📊 統計データの計算
        $totalCount = $realReviews->count();
        $averageRating = $totalCount > 0 ? $realReviews->avg('star.rating') : 4.8; // データがなければダミーの4.8を採用
        
        // 星1〜5ずつの割合と件数の初期化 (旧Restaurant側の数値をデフォルト値としてセット)
        $starsData = [
            5 => ['count' => 172, 'percentage' => 70],
            4 => ['count' => 49,  'percentage' => 20],
            3 => ['count' => 12,  'percentage' => 5],
            2 => ['count' => 12,  'percentage' => 5],
            1 => ['count' => 12,  'percentage' => 5],
        ];

        // 本物のデータがある場合は、計算したリアルな統計値で上書きする
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
            'total_reviews'  => $totalCount > 0 ? $totalCount : 245, // なければダミーの245
            'stars'          => $starsData,
            'reported_count' => 0
        ];

        // 3. 📝 表示用データ配列の作成（本物があれば本物、なければダミーをセット）
        if ($totalCount > 0) {
            $reviewCollection = $realReviews;
        } else {
            // 旧ダミーデータをPostモデルに合わせたオブジェクト形式で擬似再現
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

        // 4. Bladeテンプレート側でページネーション（linksなど）のエラーを防ぐためのモック化
        $reviews = new \Illuminate\Pagination\LengthAwarePaginator(
            $reviewCollection,
            $totalCount > 0 ? $totalCount : 1,
            10,
            1,
            ['path' => request()->url()]
        );

        return view('restaurants.reviews', compact('reviews', 'stats'));
    }

    // 📝 口コミ・レビューを保存する（Postモデルに保存）
    public function store(Request $request, $restaurant_id)
    {
        // 💡 変更：必須項目を「restaurant_id」のみとし、他は任意（nullable）に変更。動画形式も許可！
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'description'   => 'nullable|string|max:1000', // 💡 必須を解除
            'media'         => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,ogg,qt|max:51200', // 💡 imageからfileに変更し、動画も許可（最大50MB）
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // 💡 マイページから送信される場合、URLの $restaurant_id ではなくフォームで選ばれたIDを優先する
        $finalRestaurantId = $request->input('restaurant_id', $restaurant_id);

        // 💡 メディア（画像または動画）の保存処理
        $mediaPath = null;
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $extension = $file->getClientOriginalExtension();
            
            // 拡張子を判別してフォルダを分ける（posts/images または posts/videos）
            $folder = in_array($extension, ['mp4', 'mov', 'ogg', 'qt']) ? 'posts/videos' : 'posts/images';
            
            $path = $file->store($folder, 'public');
            $mediaPath = 'storage/' . $path; // 既存のカラム名「image」にそのままパスを保存
        }

        // 口コミの作成（descriptionも空欄を許可）
        $post = Post::create([
            'user_id'       => $user->id,
            'restaurant_id' => $finalRestaurantId,
            'description'   => $request->description, 
            'image'         => $mediaPath, // 画像・動画どちらのパスもここに入ります
        ]);

        // 💡 評価（rating）が選択されている場合のみ星を作成
        if ($request->filled('rating')) {
            $post->star()->create([
                'rating'  => $request->rating,
                'user_id' => $user->id
            ]);
        }

        // 💡 投稿完了後は、元のマイページに戻るため redirect()->route() に変更
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