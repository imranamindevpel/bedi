<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductListingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\StripeController;

Route::get('/', function () {
    return view('index');
});
Route::get('/admin', function () {
    return view('backend.dashboard');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
  
Route::group(['middleware' => ['auth']], function() {
    Route::resource('backend/roles', RoleController::class);
    Route::resource('backend/users', UserController::class);
    Route::resource('backend/products', ProductController::class);
    Route::resource('backend/orders', OrderController::class);
});

Route::get('stripe', [StripeController::class, 'stripePost'])->name('stripe.post');
Route::get('/stripe/success', [StripeController::class, 'handlePaymentSuccess'])->name('stripe.payment.success');
Route::get('order/status/{id}', [StripeController::class, 'order_status'])->name('order.status');
Route::get('/users/get_users_data', [UserController::class, 'get_users_data'])->name('get_users_data');
