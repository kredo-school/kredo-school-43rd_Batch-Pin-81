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

        return view('restaurant.reviews', compact('stats', 'reviews'));
    }
}
