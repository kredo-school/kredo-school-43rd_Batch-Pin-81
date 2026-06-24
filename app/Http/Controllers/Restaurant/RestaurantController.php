<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{

    public function create()
    {
        return view('auth.restaurant_register');
    }

    public function register(Request $request)
    {
        // register restaurant
        $validated = $request->validate([
            'restaurant_name' => 'required|max:255',
            'postal_code' => 'required|max:20',
            'prefecture' => 'required|max:255',
            'address' => 'required|max:255',
            'phone_number' => 'nullable|max:20',
            'description' => 'nullable',
            'business_license' => 'nullable',
        ]);

        $fullAddress = $validated['postal_code']
            . ' '
            . $validated['prefecture']
            . ' '
            . $validated['address'];

        Restaurant::create([
            'user_id' => Auth::id(),
            'restaurant_name' => $validated['restaurant_name'],
            'address' => $fullAddress,
            'phone_number' => $validated['phone_number'],
            'description' => $validated['description'] ?? null,
            'business_license' => $validated['business_license'],
        ]);

        return redirect('/restaurant/dashboard')
            ->with('success', 'Restaurant registered successfully.');
    }
}
