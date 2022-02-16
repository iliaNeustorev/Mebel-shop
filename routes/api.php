<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
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

Route::prefix('home')->group(function() {
        Route::prefix('profile')->middleware('auth')->group(function() {
            Route::get('/', [HomeController::class, 'profile']);
            Route::post('/update', [HomeController::class, 'profile_update']);  
            Route::post('/del_address', [HomeController::class, 'del_address']);   
            Route::post('/updateAvatar', [HomeController::class, 'updateAvatar']);   
            Route::post('/addAddress', [HomeController::class, 'addAddress']);   
            Route::post('/updateMainAddress', [HomeController::class, 'updateMainAddress']);   
        });
            
});

Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function()
{
    Route::post('/showRegUsers', [AdminController::class, 'showRegUsers']);
    Route::get('/enterAsUser/{userId}', [AdminController::class, 'enterAsUser']);
    Route::post('/exportCategories', [AdminController::class, 'exportCategories']);
    Route::post('/exportProducts', [AdminController::class, 'exportProducts']);
    Route::post('/importCategories', [AdminController::class, 'importCategories']);
    Route::post('/importProducts', [AdminController::class, 'importProducts']);

    Route::prefix('/categories')->group(function() {
        Route::get('/', [AdminController::class, 'get_categories'])->name('admin_categories');
        Route::post('/add_category', [AdminController::class, 'add_category'])->name('add_and_upd_category');
        Route::post('/del_category', [AdminController::class, 'del_category'])->name('del_category');
        Route::get('/add_category/{category}', [AdminController::class, 'get_category'])->name('admin_category');
    });

    Route::prefix('/products')->group(function() {
        Route::get('/', [AdminController::class, 'get_products'])->name('admin_products');
            Route::prefix('/product')->group(function (){
                Route::get('/{category}/{id?}', [AdminController::class, 'get_product'])->name('admin_get_product');
                Route::post('/add_product', [AdminController::class, 'add_product'])->name('add_product');
                Route::post('/upd_product', [AdminController::class, 'upd_product'])->name('upd_product');
                Route::post('/del_product', [AdminController::class, 'del_product'])->name('delete_product');
            });
       
    });   
});

