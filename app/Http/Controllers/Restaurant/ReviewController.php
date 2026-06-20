<?php

// 💡 変更：ディレクトリを分けたため、名前空間に \Restaurant が追加されます
namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller; // 💡 追加：親コントローラーを読み込みます
use App\Models\Review; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
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


        /* ==========================================================
         * ✨ 現時点で必要なコーディング（フロント確認用のモック・ダミーデータ）
         * ========================================================== */

        // 1. 基本的な統計数値のセット
        $totalReviews = 245;
        $averageRating = 4.8;

        // 2. 星1〜5ずつの割合と件数（グラフの表示崩れを防ぎます）
        $stars = [
            5 => ['count' => 172, 'percentage' => 70],
            4 => ['count' => 49,  'percentage' => 20],
            3 => ['count' => 12,  'percentage' => 5],
            2 => ['count' => 12,  'percentage' => 5],
            1 => ['count' => 12,  'percentage' => 5],
        ];

        // 3. 通報された件数のセット
        $reportedCount = 0;

        // 統計データを1つの配列にまとめ直す（Bladeテンプレート側での呼び出し形式に適合）
        $stats = [
            'average_rating' => $averageRating,
            'total_reviews' => $totalReviews,
            'stars' => $stars,
            'reported_count' => $reportedCount
        ];

        // 4. レビュー一覧用のダミーデータ生成（オブジェクト形式を擬似再現してBlade内の矢印記法 -> でのエラーを回避）
        $dummyReview = (object)[
            'id' => 1,
            'rating' => 5,
            'comment' => 'Amazing experience! The chef\'s omakase was incredible. Will definitely come back.',
            'created_at' => '2026-05-10',
            'is_reported' => false,
            // リレーション先（ユーザー情報）をオブジェクトで擬似セット
            'user' => (object)[
                'name' => 'John Smith'
            ],
            // 画像一覧を空のコレクションでセット
            'images' => collect([])
        ];

        // Bladeテンプレート側の `foreach($reviews ...)` で回せるようにLaravelのLengthAwarePaginatorをモック化
        $reviews = new \Illuminate\Pagination\LengthAwarePaginator(
            [$dummyReview], // データの配列
            1,              // 総件数
            10,             // 1ページあたりの表示件数
            1,              // 現在のページ番号
            ['path' => request()->url()] // ページネーションリンク用URL
        );

        // 💡 修正：ディレクトリ指定を複数形の 'restaurants.reviews' に合わせています
        return view('restaurants.reviews', compact('stats', 'reviews'));
    }
}
