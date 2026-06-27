<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.users', compact('users'));
    }

    public function customers()
    {
        $users = User::where('role_id', 1)
            ->latest()
            ->get();

        return view(
            'admin.users',
            compact('users')
        );
    }

    public function restaurants()
    {
        $users = User::where('role_id', 2)
            ->latest()
            ->get();

        return view(
            'admin.users',
            compact('users')
        );
    }

    public function admin()
    {
        $users = User::where('role_id', 3)
            ->latest()
            ->get();

        return view(
            'admin.users',
            compact('users')
        );
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|in:1,2,3',
        ]);

        // Prevent admin from changing their own role
        if ($user->id == Auth::id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update([
            'role_id' => $request->role_id,
        ]);

        return back()->with('success', 'Role updated successfully.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        // Prevent admin from suspending themselves
        if ($user->id == Auth::id() && !$request->is_active) {

            return back()->with(
                'error',
                'You cannot suspend your own account.'
            );
        }

        $user->update([
            'is_active' => $request->is_active
        ]);

        return back()->with(
            'success',
            'Account status updated successfully.'
        );
    }

    public function destroy(User $user)
    {
        if ($user->id == Auth::id()) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        $user->delete();

        return back()->with(
            'success',
            'User deleted successfully.'
        );
    }
}
