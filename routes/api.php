<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('categories')->group(function () {
    Route::get('/get', [CategoryController::class, 'getCategories']);
    Route::get('{category}', [CategoryController::class, 'category']);
    Route::get('{category}/getProducts', [ProductController::class, 'getProducts']);
});

Route::prefix('basket')->group(function () {
    Route::get('/', [BasketController::class, 'index']);
    Route::get('/getProductsQuantity', [BasketController::class, 'getProductsQuantity']);
    Route::post('/create_order', [BasketController::class, 'create_order']);
    Route::prefix('product')->group(function () {
        Route::post('/add', [BasketController::class, 'add']);
        Route::post('/remove', [BasketController::class, 'remove']);
    });
});

Route::post('login', [LoginController::class, 'authenticate']);
Route::post('logout', [LoginController::class, 'logout']);
// Auth::routes();

