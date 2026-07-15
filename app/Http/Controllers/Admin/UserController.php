<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return $this->renderIndex($request);
    }

    public function customers(Request $request)
    {
        return $this->renderIndex($request, 1);
    }

    public function restaurants(Request $request)
    {
        return $this->renderIndex($request, 2);
    }

    public function admin(Request $request)
    {
        return $this->renderIndex($request, 3);
    }

    private function renderIndex(Request $request, ?int $roleId = null)
    {
        $search = trim((string) $request->query('search', ''));

        $query = User::query();

        if ($roleId !== null) {
            $query->where('role_id', $roleId);
        }

        if ($search !== '') {
            $query->where(function (Builder $userQuery) use ($search) {
                $userQuery->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%'])
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%');
            });
        }

        $users = $query
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users', compact('users', 'search'));
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
