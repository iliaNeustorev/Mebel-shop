<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\MainController as AdminMainController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
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
    $user = $request->session()->get('currentUser');
    return [
        'user' => $user,
        'orders' => Auth::user()->orders->count(),
        ];
});

Route::post('/tokens/create', function(Request $request) {
    if(!Auth::attempt($request->only(['email', 'password'])))
        {
        return response('Неверные данные', 422);
        }
    $token = $request->user()->createToken('api')->plainTextToken;
    return ['token' => $token];
});

Route::get('/search', [HomeController::class, 'search']);

Route::prefix('categories')->group(function () {
    Route::get('/get', [CategoryController::class, 'getCategories']);
    Route::get('/shortCategories', [CategoryController::class, 'getShortCategories']);
    Route::get('{category}', [CategoryController::class, 'category']);
    Route::get('{category}/getProducts', [ProductController::class, 'getProducts']);
    Route::get('/product/{product}', [ProductController::class, 'getProduct']);
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

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'authenticate']);
Route::post('logout', [LoginController::class, 'logout']);

Route::prefix('home')->middleware('auth')->group(function() {
        Route::prefix('profile')->group(function() {
            Route::get('/', [HomeController::class, 'profile']);
            Route::post('/update', [HomeController::class, 'profile_update']);  
            Route::post('/updatePassword',[HomeController::class, 'updatePassword']);
            Route::post('/updateAvatar', [HomeController::class, 'updateAvatar']);   
            Route::post('/del_address', [AddressController::class, 'del_address']);   
            Route::post('/addAddress', [AddressController::class, 'addAddress']);   
            Route::post('/updateMainAddress', [AddressController::class, 'updateMainAddress']); 
             
        });
        Route::prefix('orders')->group(function() {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/repeatOrder{orderId}', [OrderController::class, 'repeatOrder']);
        });       
});

Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function()
{
    Route::post('/showRegUsers', [AdminMainController::class, 'showRegUsers']);
    Route::get('/enterAsUser/{userId}', [AdminMainController::class, 'enterAsUser']);
    Route::post('/exportCategories', [AdminMainController::class, 'exportCategories']);
    Route::post('/exportProducts', [AdminMainController::class, 'exportProducts']);
    Route::post('/importCategories', [AdminMainController::class, 'importCategories']);
    Route::post('/importProducts', [AdminMainController::class, 'importProducts']);

    Route::prefix('/categories')->group(function() {
        Route::get('/', [AdminCategoryController::class, 'index']);
        Route::post('/create', [AdminCategoryController::class, 'store']);
        Route::delete('/category/{categoryId}', [AdminCategoryController::class, 'destroy']);
        Route::get('/category/{category}/edit', [AdminCategoryController::class, 'edit']);
        Route::post('/category/update', [AdminCategoryController::class, 'update']);
        Route::get('{category}/getProductsCategory', [AdminCategoryController::class, 'getProductsCategory']);
    });

    Route::prefix('/products')->group(function() {
        Route::get('/', [AdminProductController::class, 'getProducts']);
        Route::get('/product/{id}', [AdminProductController::class, 'getProduct']);
        Route::post('/addProduct', [AdminProductController::class, 'addProduct']);
        Route::post('/updProduct', [AdminProductController::class, 'updProduct']);
        Route::post('/delProducts', [AdminProductController::class, 'delProducts']);
    });   
});

