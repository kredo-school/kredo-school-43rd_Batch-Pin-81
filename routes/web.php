<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// User
Route::post('/login', [App\Http\Controllers\UserController::class, 'login'])->name('login');
Route::post('/register', [App\Http\Controllers\UserController::class, 'register'])->name('register');

// Register page for restaurant
Route::get('/restaurant/register', [App\Http\Controllers\RestaurantSearchController::class, 'register'])->name('restaurant.register');

// Page for disply restaurants after search
Route::get('/view/restaurants', [App\Http\Controllers\RestaurantSearchController::class, 'view'])->name('view.restaurants');
