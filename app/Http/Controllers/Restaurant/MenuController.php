<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        $restaurantId = 1; //仮のID,機能が出来たら下記に変更する

        // $restaurantId = Auth::user()->restaurant_id;

        $menus = Menu::where('restaurant_id', $restaurantId)->get();

        return view('restaurants.menus.menu')->with('menus', $menus);
    }

    public function store(Request $request) {

        $request->validate([
            'menu_name'     => 'required|min:1',
            'price'         => 'required|numeric',
            'menu_category' => 'required',
            'description'   => 'nullable|max:1000', // 💡 min:1 を削除（空っぽを許可）
            'menu_image'    => 'nullable|image|max:2048'
        ]);

        $menu = new Menu();

        // 仮で入れる
        $menu->restaurant_id = 1; 

        $menu->menu_name     = $request->menu_name;
        $menu->price         = $request->price;
        $menu->menu_category = $request->menu_category;

        $menu->description   = $request->description ?? '';

        if($request->hasFile('menu_image')){
            $file = $request->file('menu_image');

            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/menu'), $filename);

            $menu->menu_image = $filename;
        }

        $menu->save();

        return redirect()->route('restaurant.menu.index');
    }
    
    public function destroy($id) {
        $menu = Menu::findOrFail($id);

        $menu->delete();

        return redirect()->route('restaurant.menu.index');
    }

    public function update(Request $request, $id){
        $request->validate([
            'menu_name'     => 'required|min:1',
            'price'         => 'required|numeric',
            'menu_category' => 'required',
            'description'   => 'nullable|max:1000', // 💡 required|min:1 から nullable に変更！
            'menu_image'    => 'nullable|image|max:2048'
        ]);

        $menu = Menu::findOrFail($id);

        // 仮で入れる
        $menu->restaurant_id = 1; 

        $menu->menu_name     = $request->menu_name;
        $menu->price         = $request->price;
        $menu->menu_category = $request->menu_category;
        
        // 💡 null（空）で来たら空文字 "" に変換して確実に上書き保存する
        $menu->description   = $request->description ?? '';

        if($request->hasFile('menu_image')){
            $file = $request->file('menu_image');

            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/menu'), $filename);

            $menu->menu_image = $filename;
        }
        
        $menu->save();

        return redirect()->route('restaurant.menu.index');
    }
}