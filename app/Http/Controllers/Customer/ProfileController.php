<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $request->validate([
            'username' => 'required|string|max:255',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // ユーザー名
        $user->username = $request->username;

        // プロフィール画像
        if ($request->hasFile('avatar')) {
            // 既存の古いアバター画像をストレージから物理削除
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            // 新しい画像を storage/app/public/avatars に保存
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // データベースに保存
        $user->save();

        return redirect()->route('customer.profile');
    }
    public function destroy()
    {
        // 1. 退会処理（データを消す、または無効化する）
        // $user = Auth::user();
        // $user->delete();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }
    public function search()
    {
        return view('customer.profile');
    }
}
