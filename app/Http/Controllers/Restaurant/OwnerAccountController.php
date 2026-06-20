<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OwnerAccountController extends Controller
{
    /**
     * オーナーアカウント編集画面の表示
     */
    public function edit(Request $request)
    {
        // ログイン中のユーザー（レストランオーナー）の情報を取得
        $restaurant = Auth::user(); 

        // セッションに「認証済みフラグ」が立っているかを確認
        $isVerified = session('owner_verified', false);

        return view('restaurant.owner_account', compact('restaurant', 'isVerified'));
    }

    /**
     * 2段階認証コードのメール送信
     */
    public function sendVerificationCode(Request $request)
    {
        $restaurant = Auth::user();
        
        // 6桁のランダムな数字コードを生成
        $code = rand(100000, 999999);
        
        // セッションにコードと有効期限（5分間）を保存
        session([
            'owner_verification_code' => $code,
            'owner_verification_expires_at' => now()->addMinutes(5),
        ]);

        // 📝 実際にはここでメール送信を行います
        // Mail::raw("認証コードは {$code} です。", function ($message) use ($restaurant) {
        //     $message->to($restaurant->email)->subject('【Pin+81】オーナー認証コード');
        // });

        return response()->json(['success' => true, 'message' => 'Verification code sent successfully.']);
    }

    /**
     * 2段階認証コードの検証
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        $sessionCode = session('owner_verification_code');
        $expiresAt = session('owner_verification_expires_at');

        // コードの一致および有効期限のチェック
        if ($sessionCode && $expiresAt && now()->lessThan($expiresAt) && $request->code == $sessionCode) {
            // 認証成功フラグをセッションに格納
            session(['owner_verified' => true]);
            
            // 使用済みのコードをクリア
            session()->forget(['owner_verification_code', 'owner_verification_expires_at']);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid or expired verification code.'], 422);
    }
    public function update(Request $request)
    {
        // 事前に二段階認証が済んでいるかバックエンド側でも防衛バリデーション
        if (!session('owner_verified', false)) {
            return redirect()->route('restaurant.settings.owner_account.edit')
                ->with('error', 'Unauthorized access. Please verify your identity first.');
        }

        $restaurant = Auth::user();

        $request->validate([
            'owner_name' => 'required|string|max:255',
            'manager_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // データの更新処理
        $restaurant->owner_name = $request->owner_name;
        $restaurant->manager_name = $request->manager_name;
        $restaurant->phone = $request->phone;
        
        if ($request->filled('password')) {
            $restaurant->password = bcrypt($request->password);
        }
        
        // $restaurant->save(); // データベースに保存

        return redirect()->route('restaurant.settings.owner_account.edit')
            ->with('success', 'Account information updated successfully.');
    }
}
