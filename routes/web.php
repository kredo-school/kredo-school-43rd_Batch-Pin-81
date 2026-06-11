<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

#RESTAURANT
// middlewareがないと、routeを書き換えてcustomerのroleIDの人が中に入れてしまうので必須, asはnameの前につくやつ
Route::group(['prefix' => 'restaurant', 'as' => 'restaurant.', /*'middleware' => 'restau rant'*/], function () {
 Route::get('/dashboard', [ReservationController::class, 'dashboard'])->name('dashboard');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::get('/photo', [PhotoController::class, 'index'])->name('photos');
});

// User
Route::post('/login', [App\Http\Controllers\UserController::class, 'login'])->name('login');
Route::post('/register', [App\Http\Controllers\UserController::class, 'register'])->name('register');

// Register page for restaurant
Route::get('/restaurant/register', [App\Http\Controllers\RestaurantSearchController::class, 'register'])->name('restaurant.register');
// Page for disply restaurants after search
Route::get('/view/restaurants', [App\Http\Controllers\RestaurantSearchController::class, 'view'])->name('view.restaurants');

//Customer
Route::group(['prefix' => 'customer', 'as' => 'customer.', /*'middleware' => 'restau rant'*/], function() {

    Route::get('/search', [CustomerController::class, 'index'])->name('search');
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [CustomerController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [CustomerController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my_page', [ReviewController::class, 'myPage'])->name('my_page');
});

// Page for disply restaurants after search
Route::get('/view/restaurants', [App\Http\Controllers\RestaurantSearchController::class, 'view'])->name('view.restaurants');

