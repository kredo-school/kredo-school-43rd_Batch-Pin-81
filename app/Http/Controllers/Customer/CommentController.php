<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $comment = Comment::create([
            'body'    => $request->body,
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        return response()->json([
            'success'     => true,
            'comment_id'  => $comment->id,
            'body'        => e($comment->body),
            'created_at'  => $comment->created_at->format('Y-m-d H:i'),
            'display_id'  => $user->username,
            'user_avatar' => $user->avatar,
        ]);
    }

    public function showMyPage()
    {
        $comments = Comment::orderBy('created_at', 'desc')->get();

        return view('my_page', compact('comments'));
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);
        $comment->update([
            'body' => $request->body,
        ]);

        return redirect()->back()->with('success', 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

    public function report(Comment $comment)
    {
        if ($comment->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot report your own comment.');
        }
        $comment->update([
            'is_reported' => true,
            'report_count' => $comment->report_count + 1
        ]);

        return redirect()->back()->with('success', 'Comment reported successfully.');
    }
}