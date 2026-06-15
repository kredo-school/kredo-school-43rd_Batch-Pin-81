<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile()
    {
        return view('customer.profile');
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
        ]);

        // 2. 実際の保存処理（例：ログイン中ユーザーのデータを上書き）
        // $user = Auth::user();
        // $user->update($request->all());

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }
    public function destroy()
    {
        // 1. 退会処理（データを消す、または無効化する）
        // $user = Auth::user();
        // $user->delete();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }
}
