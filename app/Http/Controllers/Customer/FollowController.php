<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle(User $user): JsonResponse
    {
        /** @var User $me */
        $me = Auth::user();

        if ($me->isFollowing($user->id)) {
            $me->followings()->detach($user->id);
            $isFollowing = false;
        } else {
            $me->followings()->attach($user->id);
            $isFollowing = true;
        }
        $followersCount = $user->followers()->count();

        return response()->json([
            'success' => true,
            'is_following' => $isFollowing,
            'followers_count' => $followersCount
        ]);
    }
    public function showProfile(User $user)
    {
        $followers = $user->followers;
        $followings = $user->followings;

        return view('customer.user.profile', compact('user', 'followers', 'followings'));
    }
}
