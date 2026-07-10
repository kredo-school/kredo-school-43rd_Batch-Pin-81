<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login form.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            $user = Auth::user();

            // Check if account is suspended
            if (!$user->is_active) {

                Auth::logout();

                return back()->withErrors([
                    'email' => 'Your account is currently suspended and cannot be used.'
                ]);
            }

            $request->session()->regenerate();

            if ($user->role_id == User::ROLE_RESTAURANT) {
                return redirect()->route('restaurant.dashboard');
            }

            return redirect()->route('customer.search');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    /**
     * Logout user.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
