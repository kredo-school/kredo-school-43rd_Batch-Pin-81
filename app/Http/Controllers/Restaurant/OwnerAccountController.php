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
     * オーナーアカウント編集画面の表示(仮)
     */
    public function edit(Request $request)
    {
        // ログイン中のユーザー情報を取得（ログインしていなくても動くようにダミー付き）
        $restaurant = Auth::user() ?? (object)[
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'email' => 'owner@restaurant.jp',
            'phone' => '+81-90-1234-5678'
        ]; 

        $isVerified = session('owner_verified', false);

        // 🔒 restaurants.settings.owner_account にしっかり指定します
        return view('restaurants.settings.owner_account', compact('restaurant', 'isVerified'));
    }
    /**
     * オーナーアカウント編集画面の表示 (本番Back code)
     */
    // public function edit(Request $request)
    // {
    //     // ログイン中のユーザー（レストランオーナー）の情報を取得
    //     $restaurant = Auth::user(); 

    //     // セッションに「認証済みフラグ」が立っているかを確認
    //     $isVerified = session('owner_verified', false);

    //     return view('restaurant.owner_account', compact('restaurant', 'isVerified'));
    // }

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
        return redirect()->route('restaurant.owner_account.edit')
            ->with('error', 'Unauthorized access. Please verify your identity first.');
    }

    $restaurant = Auth::user();

    $request->validate([
        'last_name'    => 'required|string|max:255',
        'first_name'   => 'required|string|max:255',
        'phone'        => 'required|string|max:20',
        'password'     => 'nullable|string|min:8|confirmed',
    ]);

    // ※ テスト環境等でAuth::user()がnullの場合は、保存処理をスキップするガードを入れています
    if ($restaurant) {
        $restaurant->last_name = $request->last_name;
        $restaurant->first_name = $request->first_name;
        $restaurant->phone = $request->phone;
        
        if ($request->filled('password')) {
            $restaurant->password = bcrypt($request->password);
        }
        
        // $restaurant->save(); // 本番時はここのコメントアウトを外してデータベースに保存
    }

    return redirect()->route('restaurant.owner_account.edit')
        ->with('success', 'Account information updated successfully.');
}
}
