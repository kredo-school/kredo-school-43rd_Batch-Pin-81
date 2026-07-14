<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
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

        $menus = $restaurant->menus()->get();

        return view('restaurants.menus.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_name'     => 'required|min:1',
            'price'         => 'required|numeric',
            'menu_category' => 'required',
            'description'   => 'nullable|max:1000',
            'menu_image'    => 'nullable|image|max:2048',
        ]);

        $restaurant = $this->currentRestaurant();

        $menu = new Menu();

        $menu->menu_name     = $request->menu_name;
        $menu->price         = $request->price;
        $menu->menu_category = $request->menu_category;
        $menu->description   = $request->description ?? '';

        if ($request->hasFile('menu_image')) {
            $file = $request->file('menu_image');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('assets/images/menu'),
                $filename
            );

            $menu->menu_image = $filename;
        }

        // ログイン中のユーザーが所有するレストランに紐付ける
        $restaurant->menus()->save($menu);

        return redirect()->route('restaurant.menu.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'menu_name'     => 'required|min:1',
            'price'         => 'required|numeric',
            'menu_category' => 'required',
            'description'   => 'nullable|max:1000',
            'menu_image'    => 'nullable|image|max:2048',
        ]);

        $restaurant = $this->currentRestaurant();

        // 自分のレストランに属するメニューだけを取得
        $menu = $restaurant->menus()->findOrFail($id);

        $menu->menu_name     = $request->menu_name;
        $menu->price         = $request->price;
        $menu->menu_category = $request->menu_category;
        $menu->description   = $request->description ?? '';

        if ($request->hasFile('menu_image')) {
            $file = $request->file('menu_image');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('assets/images/menu'),
                $filename
            );

            $menu->menu_image = $filename;
        }

        $menu->save();

        return redirect()->route('restaurant.menu.index');
    }

    public function destroy($id)
    {
        $restaurant = $this->currentRestaurant();

        // 他店舗のメニューは削除できない
        $menu = $restaurant->menus()->findOrFail($id);

        $menu->delete();

        return redirect()->route('restaurant.menu.index');
    }
}
