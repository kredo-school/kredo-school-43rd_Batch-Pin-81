<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\ContactController as CustomerContactController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\FavoriteController;
use App\Http\Controllers\Customer\MyReservationController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\RestaurantSearchController;
use App\Http\Controllers\Customer\PostController;
use App\Http\Controllers\Customer\UserController;
use App\Http\Controllers\Customer\BookingController;

use App\Http\Controllers\Restaurant\RestaurantController;
use App\Http\Controllers\Restaurant\MenuController;
use App\Http\Controllers\Restaurant\NotificationController as RestaurantNotificationController;
use App\Http\Controllers\Restaurant\OwnerAccountController;
use App\Http\Controllers\Restaurant\PhotoController;
use App\Http\Controllers\Restaurant\ProfileController as RestaurantProfileController;
use App\Http\Controllers\Restaurant\ReservationController;
use App\Http\Controllers\Restaurant\ContactController as RestaurantContactController;
use App\Http\Controllers\Restaurant\DashboardController;


use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;

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

  Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

  // dissplay Home page
  Route::get('/customer/search', [CustomerController::class, 'index'])->name('customer.search');

  // Favorites Page
  Route::get('/favorites', [FavoriteController::class, 'view'])->name('favorites.index');
  Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

  // My Reservations Page
  Route::get('/my_reservations', [MyReservationController::class, 'index'])->name('my_reservations');
  Route::get('/my_reservations/{reservation}/edit', [MyReservationController::class, 'edit'])->name('my_reservations.edit');
  Route::put('/my_reservations/{reservation}', [MyReservationController::class, 'update'])->name('my_reservations.update');
  Route::post('/my_reservations/{reservation}/notify-late', [MyReservationController::class, 'notifyLate'])->name('my_reservations.notify-late');
  Route::delete('/my_reservations/{reservation}', [MyReservationController::class, 'destroy'])->name('my_reservations.destroy');

  //POST
  Route::get('/my_page', [PostController::class, 'myPage'])->name('customer.mypage');
  Route::post('/restaurants/{restaurant_id}/post', [PostController::class, 'store'])->name('posts.store');
  Route::get('/restaurants/{restaurant_id}/reviews', [PostController::class, 'showRestaurantReviews'])->name('restaurant.reviews.index');

  //Profile
  Route::get('/profile', [ProfileController::class, 'profile'])->name('customer.profile');

  //Contact
  Route::get('/contact', [CustomerContactController::class, 'index'])->name('contact.index');
  Route::delete('/customer/contact/{contact}', [CustomerContactController::class, 'destroy'])->name('customer.contact.destroy');

  // Register page for restaurant
  Route::get('/restaurant/register', [RestaurantController::class, 'create'])->name('register.restaurant');
  Route::post('/restaurant/register', [RestaurantController::class, 'store'])
    ->name('restaurant.store');
  Route::get('/restaurant/thank-you', function () {
    return view('customers.thankyou');
  })->name('restaurant.thankyou');

  // Settings
  Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::patch('/settings/profile', [UserController::class, 'updateProfile'])->name('settings.profile.update');
    Route::patch('/settings/password', [UserController::class, 'updatePassword'])->name('settings.password.update');
  });
});

// Admin Page
Route::middleware(['auth', 'admin'])
  ->prefix('admin')
  ->group(function () {

    // Notifications
    Route::get('/notifications', [AdminNotificationController::class, 'index'])
      ->name('admin.notifications');

    // Users dashboard
    Route::get('/users', [AdminUserController::class, 'index'])
      ->name('admin.users');
    Route::get('/users/customers', [AdminUserController::class, 'customers'])
      ->name('admin.users.customers');
    Route::get('/users/restaurants', [AdminUserController::class, 'restaurants'])
      ->name('admin.users.restaurants');
    Route::get('/users/admin', [AdminUserController::class, 'admin'])
      ->name('admin.users.admin');
    // Roles
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])
      ->name('admin.users.role');
    // Status of Users
    Route::patch('/users/{user}/status', [AdminUserController::class, 'updateStatus'])
      ->name('admin.users.status');
    // Delete user
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
      ->name('admin.users.destroy');


    // Admin restaurants dashboard
    Route::get('/restaurants', [AdminRestaurantController::class, 'index'])
      ->name('admin.restaurants');

    // Notifications
    Route::get('/notifications', [AdminNotificationController::class, 'index'])
      ->name('admin.notifications');

    // Restaurants
    Route::patch(
      '/restaurants/{restaurant}/approve',
      [AdminRestaurantController::class, 'approve']
    )
      ->name('admin.restaurants.approve');
    Route::patch(
      '/restaurants/{restaurant}/reject',
      [AdminRestaurantController::class, 'reject']
    )
      ->name('admin.restaurants.reject');

    // Status of Restaurants
    Route::get('/restaurants/pending', [AdminRestaurantController::class, 'pending'])
      ->name('admin.restaurants.pending');
    Route::get('/restaurants/active', [AdminRestaurantController::class, 'active'])
      ->name('admin.restaurants.active');
    Route::get('/restaurants/rejected', [AdminRestaurantController::class, 'rejected'])
      ->name('admin.restaurants.rejected');
    Route::get('/restaurants/suspended', [AdminRestaurantController::class, 'suspended'])
      ->name('admin.restaurants.suspended');
    Route::patch('/restaurants/{restaurant}/status', [AdminRestaurantController::class, 'updateStatus'])
      ->name('admin.restaurants.status');

    // Display restaurant details
    Route::get('/restaurants/{restaurant}', [AdminRestaurantController::class, 'show'])
      ->name('admin.restaurants.show');
    Route::get('/restaurants/{restaurant}/edit', [AdminRestaurantController::class, 'edit'])
      ->name('admin.restaurants.edit');
    Route::patch('/restaurants/{restaurant}', [AdminRestaurantController::class, 'update'])
      ->name('admin.restaurants.update');

    // Delete restaurant
    Route::delete('/restaurants/{restaurant}', [AdminRestaurantController::class, 'destroy'])
      ->name('admin.restaurants.destroy');


    // Reservations dashboard
    Route::get('/reservations', [ReservationController::class, 'index'])
      ->name('admin.reservations');

    // Reviews dashboard
    Route::get('/reviews', [PostController::class, 'index'])
      ->name('admin.reviews');

    // Categories & Features dashboard
    Route::get('/categories&features', [PostController::class, 'index'])
      ->name('admin.categories_features');
  });

#RESTAURANT
// middlewareがないと、routeを書き換えてcustomerのroleIDの人が中に入れてしまうので必須, asはnameの前につくやつ
Route::group(['prefix' => 'restaurant', 'as' => 'restaurant.', 'middleware' => ['auth', 'restaurant']], function () {
  Route::get('/dashboard', [ReservationController::class, 'dashboard'])->name('dashboard');
Route::group(['prefix' => 'restaurant', 'as' => 'restaurant.', /*'middleware' => ['auth', 'restaurant']*/], function () {

  // Index
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  Route::post('/tables', [DashboardController::class, 'storeTable'])->name('tables.store');
  Route::put('/tables/{table}', [DashboardController::class, 'updateTable'])->name('tables.update');
  Route::delete('/tables/{table}', [DashboardController::class, 'destroyTable'])->name('tables.destroy');

  // Reservation
  Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
  Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.update_status');


  // Menu
  Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
  Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store');
  Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
  Route::patch('/menu/{id}/update', [MenuController::class, 'update'])->name('menu.update');
  Route::delete('menu/{id}/destroy', [MenuController::class, 'destroy'])->name('menu.destroy');

  // Photo
  Route::get('/photos', [PhotoController::class, 'index'])->name('photos.index');
  Route::post('/photos/store', [PhotoController::class, 'store'])->name('photos.store');
  Route::get('/photos/{id}/edit', [PhotoController::class, 'edit'])->name('photos.edit');
  Route::patch('/photos/{id}/update', [PhotoController::class, 'update'])->name('photos.update');
  Route::delete('photos/{id}', [PhotoController::class, 'destroy'])->name('photos.destroy');

  Route::get('/notifications', [RestaurantNotificationController::class, 'index'])->name('notifications');

  // Profile
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
  Route::post('/contact/{id}/reply', [RestaurantContactController::class, 'reply'])->name('contact.reply');
  Route::delete('/contact/{id}', [RestaurantContactController::class, 'destroy'])->name('contact.destroy');
});

Route::middleware(['auth'])->prefix('restaurant')->name('restaurant.')->group(function () {
  Route::get('/reviews', [RestaurantController::class, 'reviews'])->name('reviews');
});

//Customer
Route::group(['prefix' => 'customer', 'as' => 'customer.', /*'middleware' => 'restaurant'*/], function () {

  Route::get('/search', [CustomerController::class, 'index'])->name('search');

  // Profile
  Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
  Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');

  Route::get('/my_page', [PostController::class, 'myPage'])->name('my_page');
  Route::get('/reviews', [PostController::class, 'index'])->name('reviews.index');
  Route::post('/user/{user}/follow', [PostController::class, 'toggleFollow'])->name('user.follow');
  Route::get('/user/{user}/profile', [PostController::class, 'userProfile'])->name('user.profile');
  Route::get('/contact', [CustomerContactController::class, 'index'])->name('contact.index');
  Route::post('/contact', [CustomerContactController::class, 'send'])->name('contact.send');
  Route::delete('/contact/{contact}', [CustomerContactController::class, 'destroy'])->name('contact.destroy');
  Route::get('/notifications', [CustomerNotificationController::class, 'index'])->name('notifications');
});

// Page for display restaurants after search
Route::get('/restaurants/view', [RestaurantSearchController::class, 'view'])
  ->name('restaurants.view');
// Restaurant Page
Route::get('/restaurant/{restaurant}', [RestaurantSearchController::class, 'show'])
  ->name('restaurant.show');

// Display booking form page
Route::get('/booking/{restaurant}', [BookingController::class, 'create'])
  ->name('booking.create');
Route::post('/booking/confirmation', [RestaurantSearchController::class, 'store'])
  ->name('booking.store');
Route::get('/booking/confirmation', function () {
  return view('customers.restaurants.booking_confirmation');
})
  ->name('booking.confirmation');
