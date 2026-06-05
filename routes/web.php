<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\CustomerController;

// URL: http://localhost:8000/customer にアクセスしたときにコントローラーを呼び出す
Route::get('/customer', [CustomerController::class, 'index']);