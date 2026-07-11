<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $currentTab = $request->query('tab', 'all');
        $query = Post::with(['user', 'restaurant']);

        switch ($currentTab) {
            case 'visible':
                $query->where('status', 'visible');
                break;
            case 'hidden':
                $query->where('status', 'hidden');
                break;
            case 'reported':
                $query->where('is_reported', true);
                break;
            case 'all':
            default:
                break;
        }

        $reviews = $query->latest()->get();
        $counts = [
            'all'      => Post::count(),
            'visible'  => Post::where('status', 'visible')->count(),
            'hidden'   => Post::where('status', 'hidden')->count(),
            'reported' => Post::where('is_reported', true)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'currentTab', 'counts'));
    } // ← indexメソッドの終わり

    // Show / Hide
    public function toggleStatus($id)
    {
        $review = Post::findOrFail($id);

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