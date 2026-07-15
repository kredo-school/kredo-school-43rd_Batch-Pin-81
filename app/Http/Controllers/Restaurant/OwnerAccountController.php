<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OwnerAccountController extends Controller
{
    # オーナーアカウント編集画面の表示
    public function edit(Request $request)
    {
        $user = Auth::user();

        $restaurant = $user->restaurant ?? (object)[
            'bank_name' => null,
            'branch_code' => null,
            'account_number' => null,
            'account_holder_name' => null,
            'subscription_plan' => 'basic'
        ];

        $isVerified = session('owner_verified', false);

        return view('restaurants.settings.owner_account', compact('user', 'restaurant', 'isVerified'));
    }

    # 2段階認証コードの送信 (Email / SMS)
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'method' => 'required|in:email,sms',
        ]);

        $restaurant = Auth::user();
        if (!$restaurant) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
        }
        $code = rand(100000, 999999);

        session([
            'owner_verification_code' => $code,
            'owner_verification_expires_at' => now()->addMinutes(5),
        ]);

        if ($request->input('method') === 'email') {
            Mail::raw("Your verification code is {$code}.", function ($message) use ($restaurant) {
                $message->to($restaurant->email)->subject('[Pin+81] Owner Verification Code');
            });
            Log::info("Email Verification Code for Restaurant ID {$restaurant->id}: {$code}");
        } else {
            Log::info("SMS Verification Code for Restaurant ID {$restaurant->id} ({$restaurant->phone}): {$code}");
        }

        return response()->json(['success' => true, 'message' => 'Verification code sent successfully.']);
    }

    # 2段階認証コードの検証
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        $sessionCode = session('owner_verification_code');
        $expiresAt = session('owner_verification_expires_at');

        if ($sessionCode && $expiresAt && now()->lessThan($expiresAt) && $request->code == $sessionCode) {
            session(['owner_verified' => true]);
            session()->forget(['owner_verification_code', 'owner_verification_expires_at']);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid or expired verification code.'], 422);
    }

    # アカウント情報・支払い情報・サブスクの一括更新
    public function update(Request $request)
    {
        if (!session('owner_verified', false)) {
            return redirect()->route('restaurant.settings.owner_account.edit')
                ->with('error', 'Unauthorized access. Please verify your identity first.');
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'last_name'           => 'required|string|max:255',
            'first_name'          => 'required|string|max:255',
            'email'               => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone'               => 'required|string|max:20',
            'password'            => 'nullable|string|min:8|confirmed',
            'bank_name'           => 'nullable|string|max:255',
            'branch_code'         => 'nullable|string|max:10',
            'account_number'      => 'nullable|string|max:20',
            'account_holder_name' => 'nullable|string|max:255',
            'subscription_plan'   => 'required|in:basic,pro,enterprise',
        ]);

        if ($user) {
            if ($user->email !== $request->email) {
                $user->email = $request->email;
                $user->email_verified_at = null;
            }

            $user->last_name = $request->last_name;
            $user->first_name = $request->first_name;

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            $restaurant = $user->restaurant;
            if ($restaurant) {
                $restaurant->phone_number = $request->phone;
                $restaurant->bank_name = $request->bank_name;
                $restaurant->branch_code = $request->branch_code;
                $restaurant->account_number = $request->account_number;

                if ($request->filled('account_holder_name')) {
                    $cleanedName = mb_convert_kana($request->account_holder_name, 'askv', 'UTF-8');
                    $restaurant->account_holder_name = strtoupper($cleanedName);
                }

                $restaurant->subscription_plan = $request->subscription_plan;
                $restaurant->save();
            }
        }

        return redirect()->route('restaurant.settings.owner_account.edit')
            ->with('success', 'Account, payment, and subscription information updated successfully.');
    }
}
