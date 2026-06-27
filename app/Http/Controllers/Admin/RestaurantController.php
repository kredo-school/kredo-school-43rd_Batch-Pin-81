<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::orderBy('created_at', 'desc')->get();

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function pending()
    {
        $restaurants = Restaurant::where('status', 1)
            ->latest()
            ->get();

        return view(
            'admin.restaurants',
            compact('restaurants')
        );
    }

    public function active()
    {
        $restaurants = Restaurant::where('status', 2)
            ->latest()
            ->get();

        return view(
            'admin.restaurants',
            compact('restaurants')
        );
    }

    public function rejected()
    {
        $restaurants = Restaurant::where('status', 3)
            ->latest()
            ->get();

        return view(
            'admin.restaurants',
            compact('restaurants')
        );
    }

    public function updateStatus(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $restaurant->update([
            'is_active' => $request->is_active
        ]);

        return back()->with(
            'success',
            'Restaurant status updated successfully.'
        );
    }

    public function show(Restaurant $restaurant)
    {
        return view('admin.restaurants.show', compact('restaurant'));
    }
}
