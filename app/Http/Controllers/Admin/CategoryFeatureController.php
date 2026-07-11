<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryFeatureController extends Controller
{
  public function index()
  {
    $categories = Category::withCount('restaurants')
      ->orderBy('category_name')
      ->get();

    $features = Feature::withCount('restaurants')
      ->orderBy('feature_name')
      ->get();

    return view('admin.categories_features.index', compact('categories', 'features'));
  }

  public function storeCategory(Request $request)
  {
    $validated = $request->validate([
      'category_name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('categories', 'category_name')->whereNull('deleted_at'),
      ],
    ]);

    Category::create($validated);

    return back()->with('success', 'Category added successfully.');
  }

  public function updateCategory(Request $request, Category $category)
  {
    $validated = $request->validate([
      'category_name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('categories', 'category_name')
          ->ignore($category->id)
          ->whereNull('deleted_at'),
      ],
    ]);

    $category->update($validated);

    return back()->with('success', 'Category updated successfully.');
  }

  public function destroyCategory(Category $category)
  {
    $category->delete();

    return back()->with('success', 'Category deleted successfully.');
  }

  public function storeFeature(Request $request)
  {
    $validated = $request->validate([
      'feature_name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('features', 'feature_name')->whereNull('deleted_at'),
      ],
    ]);

    Feature::create($validated);

    return back()->with('success', 'Feature added successfully.');
  }

  public function updateFeature(Request $request, Feature $feature)
  {
    $validated = $request->validate([
      'feature_name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('features', 'feature_name')
          ->ignore($feature->id)
          ->whereNull('deleted_at'),
      ],
    ]);

    $feature->update($validated);

    return back()->with('success', 'Feature updated successfully.');
  }

  public function destroyFeature(Feature $feature)
  {
    $feature->delete();

    return back()->with('success', 'Feature deleted successfully.');
  }
}
