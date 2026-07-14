<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    private function currentRestaurant()
    {
        $restaurant = Auth::user()->restaurant;

        abort_unless($restaurant, 403, 'Restaurant profile not found.');

        return $restaurant;
    }

    public function index()
    {
        $restaurant = $this->currentRestaurant();

        $photos = $restaurant->photos()->get();

        return view('restaurants.photos.index', compact('photos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo_file'     => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_category' => 'required|in:food,drink,interior,exterior,other',
        ]);

        $restaurant = $this->currentRestaurant();

        $path = null;

        if ($request->hasFile('photo_file')) {
            $path = $request
                ->file('photo_file')
                ->store('restaurant_photos', 'public');
        }

        // ログイン中のユーザーが所有するレストランに紐付ける
        $restaurant->photos()->create([
            'menu_id'        => null,
            'photo_path'     => $path,
            'photo_category' => $request->photo_category,
        ]);

        return redirect()
            ->route('restaurant.photos.index')
            ->with('success', 'Photo uploaded successfully.');
    }

    public function destroy($id)
    {
        $restaurant = $this->currentRestaurant();

        // 他店舗の写真は削除できない
        $photo = $restaurant->photos()->findOrFail($id);

        if (
            $photo->photo_path &&
            Storage::disk('public')->exists($photo->photo_path)
        ) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        $photo->delete();

        return redirect()
            ->route('restaurant.photos.index')
            ->with('success', 'Photo deleted successfully.');
    }
}
