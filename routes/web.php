<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BasketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Category;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('/{any}', function () {
    return view('layouts.app');
})->where('any', '.*');
    
Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::get('/orders/repeatOrder{orderId}', [OrderController::class, 'repeatOrder'])->name('repeatOrder');



Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');





