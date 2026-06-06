<?php

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

#RESTAURANT
// middlewareがないと、routeを書き換えてcustomerのroleIDの人が中に入れてしまうので必須, asはnameの前につくやつ
Route::group(['prefix' => 'restaurant', 'as' => 'restaurant.', /*'middleware' => 'restaurant'*/], function() {

    Route::get('/dashboard', [RestaurantController::class, 'index'])->name('dashboard');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');

});
