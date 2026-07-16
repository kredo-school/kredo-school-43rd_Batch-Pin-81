<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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

    // 最新順（IDの数字順にしたい場合、('id', 'desc')を('id', 'asc')に変更)
    $areas = Area::orderBy('id', 'desc')
      ->get();

    return view('admin.categories_features.index', compact('categories', 'features', 'areas'));
  }

  /* --- Category Actions --- */

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

  /* --- Feature Actions --- */

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

  /* --- Area Actions --- */

  public function storeArea(Request $request)
  {
    $validated = $request->validate([
      'area_name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('areas', 'area_name')->whereNull('deleted_at'),
      ],
      'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
    ]);

    $data['area_name'] = $request->area_name;
    $data['image_url'] = 'https://images.unsplash.com/...';

    if ($request->hasFile('image')) {
      $path = $request->file('image')->store('areas', 'public');
      $data['image_url'] = Storage::url($path);
    } else {
      $data['image_url'] = 'https://images.unsplash.com/photo-1542051841857-5f90071e7989?w=500';
    }

    Area::create($data);

    return back()->with('success', 'Area added successfully.');
  }

  public function updateArea(Request $request, Area $area)
  {
    $validated = $request->validate([
      'area_name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('areas', 'area_name')
          ->ignore($area->id)
          ->whereNull('deleted_at'),
      ],
      'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
    ]);

    $data = [
      'area_name' => $validated['area_name'],
    ];

    if ($request->hasFile('image')) {
      if ($area->image_url && !str_starts_with($area->image_url, 'http')) {
        $oldPath = str_replace('/storage/', '', $area->image_url);
        Storage::disk('public')->delete($oldPath);
      }

      $path = $request->file('image')->store('areas', 'public');
      $data['image_url'] = Storage::url($path);
    }

    $area->update($data);

    return back()->with('success', 'Area updated successfully.');
  }

  public function destroyArea(Area $area)
  {
    $area->delete();

    return back()->with('success', 'Area deleted successfully.');
  }
}
