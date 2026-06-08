<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

#RESTAURANT
// middlewareがないと、routeを書き換えてcustomerのroleIDの人が中に入れてしまうので必須, asはnameの前につくやつ
Route::group(['prefix' => 'restaurant', 'as' => 'restaurant.', /*'middleware' => 'restau rant'*/], function() {

    Route::get('/dashboard', [ReservationController::class, 'dashboard'])->name('dashboard');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

});

Route::group(['prefix' => 'customer', 'as' => 'customer.', /*'middleware' => 'restaurant'*/], function() {

    Route::get('/search', [CustomerController::class, 'index'])->name('search');

});