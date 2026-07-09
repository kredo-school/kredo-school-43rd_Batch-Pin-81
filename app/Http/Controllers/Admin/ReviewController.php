<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // クエリパラメータから 'tab' を取得（デフォルトは 'all'）
        $currentTab = $request->query('tab', 'all');

        // ベースとなるクエリ（ユーザー、店舗、評価をまとめて取得）
        $query = Post::with(['user', 'restaurant', 'star']);

        // 添付写真の要件に合わせたタブの条件分岐
        switch ($currentTab) {
            case 'visible':
                // status が 'visible' のもの（または未定義をデフォルトとするなら hidden 以外）
                $query->where('status', 'visible');
                break;
            case 'hidden':
                $query->where('status', 'hidden');
                break;
            case 'reported':
                // レストラン側等から通報（is_reported = true）されているもの
                $query->where('is_reported', true);
                break;
            case 'all':
            default:
                // 条件なし（すべて）
                break;
        }

        // 管理画面のリストに合わせて最新順に取得
        $reviews = $query->latest()->get();

        // 各タブの横に表示する件数を集計（バッジ用）
        $counts = [
            'all'      => Post::count(),
            'visible'  => Post::where('status', 'visible')->count(),
            'hidden'   => Post::where('status', 'hidden')->count(),
            'reported' => Post::where('is_reported', true)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'currentTab', 'counts'));
    }

    // 各口コミの Show / Hide 状態を切り替えるアクション
    public function toggleStatus($id)
    {
        $review = Post::findOrFail($id);

        // status を反転させる（デフォルトが null もしくは 'visible' の場合は 'hidden' へ）
        if ($review->status === 'hidden') {
            $review->status = 'visible';
            $message = 'The review is now visible.';
        } else {
            $review->status = 'hidden';
            $message = 'The review has been hidden.';
        }

        $review->save();

        return redirect()->back()->with('success', $message);
    }
}