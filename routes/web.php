<?php

#Restaurant
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\ContactController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\FavoriteController;
use App\Http\Controllers\Customer\MyReservationController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\RestaurantSearchController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\UserController;

use  App\Http\Controllers\Restaurant\RestaurantController;
use App\Http\Controllers\Restaurant\MenuController;
use App\Http\Controllers\Restaurant\NotificationController as RestaurantNotificationController;
use App\Http\Controllers\Restaurant\OwnerAccountController;
use App\Http\Controllers\Restaurant\PhotoController;
use App\Http\Controllers\Restaurant\ProfileController as RestaurantProfileController;
use App\Http\Controllers\Restaurant\ReservationController;
use App\Http\Controllers\Restaurant\ReviewController as RestaurantReviewController;
use App\Http\Controllers\Restaurant\ContactController as RestaurantContactController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

  Route::get('/register', [RegisterController::class, 'create'])
    ->name('register');
  Route::post('/register', [RegisterController::class, 'store']);

  Route::get('/login', [LoginController::class, 'create'])
    ->name('login');
  Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {

  Route::post('/logout', [LoginController::class, 'destroy'])
    ->name('logout');

  // Register page for restaurant
  Route::get('/restaurant/register', [RestaurantController::class, 'registerRestaurant'])->name('register.restaurant');
  Route::post('/restaurant/register', [RestaurantController::class, 'register'])
    ->name('restaurant.store');
});



#RESTAURANT
// middlewareがないと、routeを書き換えてcustomerのroleIDの人が中に入れてしまうので必須, asはnameの前につくやつ
Route::group(['prefix' => 'restaurant', 'as' => 'restaurant.', /*'middleware' => 'restaurant'*/], function () {
  Route::get('/dashboard', [ReservationController::class, 'dashboard'])->name('dashboard');
  Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');

  // Menu
  Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
  Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store');
  Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
  Route::patch('/menu/{id}/update', [MenuController::class, 'update'])->name('menu.update');
  Route::delete('menu/{id}/destroy', [MenuController::class, 'destroy'])->name('menu.destroy');


  Route::get('/photos', [PhotoController::class, 'index'])->name('photos');
  Route::get('/notifications', [RestaurantNotificationController::class, 'index'])->name('notifications');
  Route::get('/profile', [RestaurantProfileController::class, 'edit'])->name('profile.edit');
  Route::put('/profile', [RestaurantProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('restaurant/settings')->name('restaurant.settings.')->group(function () {
  Route::get('owner_account', [OwnerAccountController::class, 'edit'])->name('owner_account.edit');
  Route::any('owner_account/send-code', [OwnerAccountController::class, 'sendVerificationCode'])->name('owner_account.send_code');
  Route::post('owner_account/verify', [OwnerAccountController::class, 'verifyCode'])->name('owner_account.verify');
  Route::put('owner_account', [OwnerAccountController::class, 'update'])->name('owner_account.update');
  Route::get('/contact', [RestaurantContactController::class, 'index'])->name('contact.index');
  Route::post('/contact', [RestaurantContactController::class, 'send'])->name('contact.send');
  Route::get('/contact/{id}', [RestaurantContactController::class, 'show'])->name('contact.show');
  Route::post('/contact/{id}/reply', [RestaurantContactController::class, 'sendFollowUp'])->name('contact.reply');
});


//Customer
Route::group(['prefix' => 'customer', 'as' => 'customer.', /*'middleware' => 'restaurant'*/], function () {

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
  Route::get('/settings', [UserController::class, 'settings'])->name('settings');
  Route::get('/notifications', [CustomerNotificationController::class, 'index'])->name('notifications');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/customer/my_page', [ReviewController::class, 'show'])->name('customer.mypage');
});

// Page for display restaurants after search
Route::get('/restaurants/view', [RestaurantSearchController::class, 'view'])->name('restaurants.view');

// Restaurant Page for customer
Route::get('/restaurant/show', [RestaurantSearchController::class, 'show'])->name('restaurant.show');
Route::get('/booking', [RestaurantSearchController::class, 'create'])->name('booking.create');
Route::post('/booking/confirmation', [RestaurantSearchController::class, 'store'])->name('booking.store');
Route::get('/booking/confirmation', function () {
  return view('customers.restaurants.booking_confirmation');
})->name('booking.confirmation');
Route::get('/restaurant/reviews', [RestaurantReviewController::class, 'index'])->name('restaurant.reviews.index');

// My Reservations Page
Route::get('/my_reservations', [MyReservationController::class, 'index'])
  ->name('my_reservations');
Route::get('/my_reservations/{reservation}/edit', [MyReservationController::class, 'edit'])
  ->name('my_reservations.edit');
Route::put('/my_reservations/{reservation}', [MyReservationController::class, 'update'])
  ->name('my_reservations.update');
Route::post('/my_reservations/{reservation}/notify-late', [MyReservationController::class, 'notifyLate'])
  ->name('my_reservations.notify-late');
Route::delete('/my_reservations/{reservation}', [MyReservationController::class, 'destroy'])
  ->name('my_reservations.destroy');

// Favorites Page
Route::get('/favorites', [FavoriteController::class, 'view'])
  ->name('favorites.index');
Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
