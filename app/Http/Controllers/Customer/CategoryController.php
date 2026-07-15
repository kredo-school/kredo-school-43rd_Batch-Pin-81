<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::select('category_name as name')->get()->toArray();

        if (empty($categories)) {
            $categories = [
                ['name' => 'Sushi'],
                ['name' => 'Ramen'],
                ['name' => 'Yakitori'],
                ['name' => 'Kaiseki'],
                ['name' => 'Izakaya'],
            ];
        }

        return view('customer.categories', compact('categories'));
    }

    public function show($category)
    {
        $restaurants = Restaurant::with(['photos', 'categories', 'features'])
            ->approved()
            ->withAvg('posts', 'rating')
            ->where(function ($query) use ($category) {
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->where('category_name', 'LIKE', '%' . $category . '%');
                })
                ->orWhere('restaurant_name', 'LIKE', '%' . $category . '%')
                ->orWhere('description', 'LIKE', '%' . $category . '%');
            })
            ->get();

        return view('customer.category', compact('restaurants', 'category'));
    }
}