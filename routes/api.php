<?php

use App\Http\Controllers\Category;
use Illuminate\Http\Request;
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
    Route::get('/get', [Category::class, 'getCategoires']);
    Route::get('{category}', [Category::class, 'category']);
    Route::get('{category}/getProducts', [ProductController::class, 'getProducts']);
});



