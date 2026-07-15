<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Restaurant::approved()
            ->select('city as name')
            ->whereNotNull('city')
            ->distinct()
            ->get()
            ->toArray();

        return view('customer.areas', compact('areas'));
    }

    public function show($area)
    {
        $restaurants = Restaurant::with(['photos', 'categories', 'features'])
            ->approved()
            ->withAvg('posts', 'rating')
            ->where(function ($query) use ($area) {
                $query->where('city', 'LIKE', '%' . $area . '%')
                ->orWhere('street_address_building', 'LIKE', '%' . $area . '%')
                ->orWhere('description', 'LIKE', '%' . $area . '%');
            })
            ->get();
        return view('customer.area', compact('restaurants', 'area'));
    }
}