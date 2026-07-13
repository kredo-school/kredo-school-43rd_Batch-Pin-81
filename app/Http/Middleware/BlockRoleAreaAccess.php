<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BlockRoleAreaAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Restaurant accounts should not open Customer-only pages by typing the URL.
        if ((int) $user->role_id === 2 && $this->isCustomerOnlyArea($request)) {
            return redirect()->route('restaurant.dashboard')
                ->with('error', 'Restaurant accounts cannot access customer pages.');
        }

        // Customer accounts should not open Restaurant management pages.
        // Public restaurant pages such as /restaurant/{restaurant} are intentionally not blocked here.
        if ((int) $user->role_id === 1 && $this->isRestaurantManagementArea($request)) {
            return redirect()->route('customer.search')
                ->with('error', 'Customer accounts cannot access restaurant management pages.');
        }

        return $next($request);
    }

    private function isCustomerOnlyArea(Request $request): bool
    {
        return $request->is('customer')
            || $request->is('customer/*')
            || $request->is('my_page')
            || $request->is('my_reservations')
            || $request->is('my_reservations/*')
            || $request->is('favorites')
            || $request->is('favorites/*')
            || $request->is('contact')
            || $request->is('contact/*')
            || $request->is('profile')
            || $request->is('profile/*');
    }

    private function isRestaurantManagementArea(Request $request): bool
    {
        return $request->is('restaurant/dashboard')
            || $request->is('restaurant/menu')
            || $request->is('restaurant/menu/*')
            || $request->is('restaurant/notifications')
            || $request->is('restaurant/photos')
            || $request->is('restaurant/photos/*')
            || $request->is('restaurant/profile')
            || $request->is('restaurant/profile/*')
            || $request->is('restaurant/reservations')
            || $request->is('restaurant/reservations/*')
            || $request->is('restaurant/reviews')
            || $request->is('restaurant/settings/*')
            || $request->is('restaurant/tables')
            || $request->is('restaurant/tables/*');
    }
}