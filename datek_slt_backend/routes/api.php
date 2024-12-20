<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductTypeController;
use App\Http\Controllers\Api\ProductImagesController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//Auth routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/auth/login', 'login')->name('login.api');
    Route::post('/auth/register', 'register')->name('register.api');
    Route::middleware('auth:api')->get('/user', 'getUserInfo');
    Route::middleware('auth:api')->post('/logout', 'logout');
});

// User routes
Route::controller(UserController::class)->group(function () {
    Route::get('users', 'index');
    Route::get('users/all', 'selectAllUser');
    Route::get('users/{id}', 'getUserById');
    Route::post('users/create', 'createUser');
    Route::put('users/{id}/edit', 'editUser');
    Route::put('users/{id}/update', 'update');
    Route::delete('users/{id}/delete', 'deleteUser');
    Route::delete('users/delete-multi-user/{users}', 'deleteMultiUser');
});

// Category routes
Route::prefix('categories')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('create', 'store');
    Route::get('{id}/edit', 'edit');
    Route::put('{id}/update', 'updateCategory');
    Route::delete('{id}/delete', 'destroy');
});

//Brand routes
Route::get('brands', [BrandController::class, 'index']);

//Product routes
Route::get('products', [ProductController::class, 'index']);
Route::get('search', [ProductController::class, 'search']);
Route::get('products/latest', [ProductController::class, 'getLatestProducts']);
Route::get('products/{id}/related', [ProductController::class, 'getRelatedProducts']);
Route::get('products/category/{slug}', [ProductController::class, 'getProductsByCategorySlug']);
Route::get('product/detail', [ProductController::class, 'show']);
Route::post('products/create', [ProductController::class, 'createProduct']);
Route::get('products/{id}/edit', [ProductController::class, 'edit']);
Route::post('products/{id}/update', [ProductController::class, 'update']);
Route::delete('products/{id}/delete', [ProductController::class, 'destroy']);
Route::delete('products/delete-multi-product/{products}', [ProductController::class, 'deleteMultiProduct']);
Route::delete('products/remove-image/{image_id}', action: [ProductController::class, 'destroyImage']);
Route::get('products/get-by-brand', [ProductController::class, 'getAllProductsByBrand']);

//Product Images routes
Route::prefix('products/{productId}/images')->controller(ProductImagesController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('create', 'store');
    Route::get('{id}', 'show');
    Route::put('{id}/update', 'update');
    Route::delete('{id}/delete', 'destroy');
});

//Cart routes
Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::post('add-to-cart', 'addToCart');
    Route::post('check-product', 'checkProduct');
    Route::post('update-quantity', 'updateQuantity');
    Route::get('get-cart', 'getCart');
    Route::get('count-items', 'countItems');
    Route::delete('remove-item/{cart_id}', 'removeItem');
});
