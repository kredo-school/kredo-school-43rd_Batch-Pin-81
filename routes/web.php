<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantSearchController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


#RESTAURANT
// middlewareがないと、routeを書き換えてcustomerのroleIDの人が中に入れてしまうので必須, asはnameの前につくやつ
Route::group(['prefix' => 'restaurant', 'as' => 'restaurant.', /*'middleware' => 'restaurant'*/], function () {
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
Route::group(['prefix' => 'customer', 'as' => 'customer.', /*'middleware' => 'restaurant'*/], function () {

  Route::get('/search', [CustomerController::class, 'index'])->name('search');
  Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
  Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
  Route::get('/my_page', [ReviewController::class, 'myPage'])->name('my_page');



  
  Route::get('/settings',[UserController::class, 'settings'])->name('settings');
});


// Page for disply restaurants after search
Route::get('/restaurants/view', [RestaurantSearchController::class, 'view'])->name('restaurants.view');

// Restaurant Page for customer
Route::get('/restaurant/show', [RestaurantSearchController::class, 'show'])->name('restaurant.show');
Route::get('/booking', [RestaurantSearchController::class, 'displayBookingPage'])->name('restaurant.book');
Route::post('/booking/confirmation', [RestaurantSearchController::class, 'store'])->name('booking.store');
Route::get('/booking/confirmation', function () {
  return view('customers.restaurants.booking_confirmation');
})->name('booking.confirmation');

// 1. My Reservations Page (The list view)
Route::get('/my_reservations', [RestaurantSearchController::class, 'index'])
  ->name('my_reservations');

// 2. Reservation Confirmation Success Page
// Passing the reservation ID or confirmation code to display the specific confirmation card
Route::get('/my_reservations/{reservation}/confirmed', [RestaurantSearchController::class, 'confirmed'])
  ->name('my_reservations.confirmed');

// 3. Edit / Change Booking Page
Route::get('/my_reservations/{reservation}/edit', [RestaurantSearchController::class, 'edit'])
  ->name('my_reservations.edit');
Route::put('/my_reservations/{reservation}', [RestaurantSearchController::class, 'update'])
  ->name('my_reservations.update');

// 4. "I'll be late" Notification (Custom Action)
Route::post('/my_reservations/{reservation}/notify-late', [RestaurantSearchController::class, 'notifyLate'])
  ->name('my_reservations.notify-late');

// 5. Cancel Booking (Destroys the resource or updates status)
Route::delete('/my_reservations/{reservation}', [RestaurantSearchController::class, 'destroy'])
  ->name('my_reservations.destroy');
