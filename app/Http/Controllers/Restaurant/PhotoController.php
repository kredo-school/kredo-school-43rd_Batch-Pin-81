<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index()
    {
        // 仮のID, あとで下に変更する
        $restaurantId = 1;

        // Auth::user()->restaurant_id;

        $photos = Photo::where('restaurant_id', $restaurantId)->get();

        return view('restaurants.photos.index')->with('photos', $photos);
    }

    public function store(Request $request)
    {

        $request->validate([
            'photo_file'     => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_category' => 'required|in:food,drink,interior,exterior,other'
        ]);

        $restaurantId = 1;

        // $restaurantId = Auth::user()->restaurant_id;

        // 画像ファイルを storage/app/public/restaurant_photos に一意の名前で保存
        $path = null;
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');

            $path = $file->store('restaurant_photos', 'public');
        }

        // データベースにPhotoオブジェクトを新規生成
        Photo::create([
            'restaurant_id'  => $restaurantId,
            'menu_id'        => null, // メニュー管理画面以外からの登録なので null で固定
            'photo_path'     => $path,
            'photo_category' => $request->photo_category,
        ]);

        return redirect()->route('restaurant.photos.index')->with('success', 'Photo uploaded successfully.');
    }

    public function destroy($id) {
        $photo = Photo::findOrFail($id);

        // 仮で入れる
        $restaurantId = 1;

        // 簡易セキュリティチェック、IDが一致しているか
        if($photo->restaurant_id !== $restaurantId) {
            abort(403, 'Unauthorized action.');
        }

        if ($photo->photo_path && Storage::disk('public')->exists($photo->photo_path)) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        $photo->delete();

        return redirect()->route('restaurant.photos.index')->with('success', 'Photo deleted successfully.');
    }
}
