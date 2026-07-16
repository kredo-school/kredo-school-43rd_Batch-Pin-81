<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    // List
    public function index()
    {
        $areas = Area::orderBy('id', 'asc')->get();
        
        return view('admin.categories_features.index', compact('areas'));
    }

    // Registration
    public function store(Request $request)
    {
        $request->validate([
            'area_name' => 'required|string|max:255|unique:areas,name',
            'image_url' => 'nullable|url|max:255',
        ]);

        Area::create([
            'name' => $request->area_name,
            'image_url' => $request->image_url,
        ]);

        return redirect()->back()->with('success', 'Area added successfully.');
    }

    // Edit
    public function edit(Area $area)
    {
        return view('admin.areas.edit', compact('area'));
    }

    // Update
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'area_name' => 'required|string|max:255|unique:areas,name,' . $area->id,
            'image_url' => 'nullable|url|max:255',
        ]);

        $area->update([
            'name' => $request->area_name,
            'image_url' => $request->image_url,
        ]);

        return redirect()->back()->with('success', 'Area updated successfully.');
    }

    // Delete
    public function destroy(Area $area)
    {
        $area->delete();

        return redirect()->back()->with('success', 'Area deleted successfully.');
    }
}