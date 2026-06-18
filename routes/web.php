<?php

use App\Http\Controllers\Customer\ContactController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RestaurantSearchController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

#RESTAURANT
// middlewareがないと、routeを書き換えてcustomerのroleIDの人が中に入れてしまうので必須, asはnameの前につくやつ
Route::group(['prefix' => 'restaurant', 'as' => 'restaurant.', /*'middleware' => 'restau rant'*/], function () {
  Route::get('/dashboard', [ReservationController::class, 'dashboard'])->name('dashboard');
  Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
  Route::get('/menu', [MenuController::class, 'index'])->name('menu');
  Route::get('/photo', [PhotoController::class, 'index'])->name('photos');
});


// User
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/register', [UserController::class, 'register'])->name('register');

// Register page for restaurant
Route::get('/restaurant/register', [UserController::class, 'registerRestaurant'])->name('register.restaurant');



//Customer
Route::group(['prefix' => 'customer', 'as' => 'customer.', /*'middleware' => 'restau rant'*/], function () {

    Route::get('/search', [CustomerController::class, 'index'])->name('search');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my_page', [ReviewController::class, 'myPage'])->name('my_page');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/user/{user}/follow', [ReviewController::class, 'toggleFollow'])->name('user.follow');
    Route::get('/user/{user}/profile', [ReviewController::class, 'userProfile'])->name('user.profile');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
});


// Page for disply restaurants after search
Route::get('/view/restaurants', [App\Http\Controllers\RestaurantSearchController::class, 'view'])->name('view.restaurants');
