<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Customer（1）とAdmin（3）はCustomer側ページにアクセス可能
        // Restaurant（2）はアクセス不可
        if (!in_array((int) $user->role_id, [1, 3], true)) {
            abort(403, 'This page is only available for customer and admin accounts.');
        }

        return $next($request);
    }
}
