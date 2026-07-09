<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $user = Auth::user();

        // ログインしていない場合はエラーを返す
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $alreadyLiked = Like::where('user_id', $user->id)
                            ->where('post_id', $post->id)
                            ->exists();

        if ($alreadyLiked) {
            Like::where('user_id', $user->id)
                ->where('post_id', $post->id)
                ->delete();
                
            $isLiked = false;
        } else {
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id
            ]);
            $isLiked = true;
        }

        $likesCount = $post->likes()->count();

        return response()->json([
            'isLiked' => $isLiked,
            'likes_count' => $likesCount
        ]);
    }
}