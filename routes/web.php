<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BasketController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return redirect()->route('home');
});

Route::prefix('basket')->group(function () {
    Route::get('/', [BasketController::class, 'index'])->name('basket');
    Route::post('/create_order', [BasketController::class, 'create_order'])->name('create_order');
    Route::prefix('product')->group(function () {
        Route::post('/add', [BasketController::class, 'add'])->name('addProduct');
        Route::post('/remove', [BasketController::class, 'remove'])->name('removeProduct');
    });
});

Route::get('/orders', [OrderController::class, 'index'])->name('orders');

Route::prefix('home')->group(function() {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::prefix('profile')->middleware('auth')->group(function() {
            Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
            Route::post('/profile/update', [HomeController::class, 'profile_update'])->name('profile_update');  
            Route::post('/del_address', [HomeController::class, 'del_address'])->name('del_address');   
        });
            
});
   

Auth::routes();

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/categories/{category}', [Category::class, 'category'])->name('category');


Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function()
{
    // Route::redirect('/', '/admin/categories')->name('edit_categories');
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    
    Route::get('/enterAsUser/{userId}', [AdminController::class, 'enterAsUser'])->name('enterAsUser');
    Route::post('/exportCategories', [AdminController::class, 'exportCategories'])->name('exportCategories');
    Route::post('/importCategories', [AdminController::class, 'importCategories'])->name('importCategories');

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



