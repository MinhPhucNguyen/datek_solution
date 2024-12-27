<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductImagesController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\IncomeTrackerController;

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
Route::get('categories', [CategoryController::class, 'index']);
Route::post('categories/create', [CategoryController::class, 'store']);
Route::get('categories/{id}/edit', [CategoryController::class, 'edit']);
Route::put('categories/{id}/update', [CategoryController::class, 'updateCategory']);
Route::delete('categories/{id}/delete', [CategoryController::class, 'destroy']);
Route::get('category-products/{slug}', [CategoryController::class, 'getCategoryProducts']);

//Brand routes
Route::get('brands', [BrandController::class, 'index']);
Route::post('brands/create', [BrandController::class, 'create']);
Route::post('brands/{id}/update', [BrandController::class, 'update']);
Route::delete('brands/{id}/delete', [BrandController::class, 'delete']);
Route::get('brands/{id}', [BrandController::class, 'getBrandById']);

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
Route::get('products/get-by-brand/{brand_id}', [ProductController::class, 'getAllProductsByBrand']);

//Product Images routes
Route::get('products/{productId}/images', [ProductImagesController::class, 'index']);
Route::post('products/{productId}/images/create', [ProductImagesController::class, 'store']);
Route::get('products/{productId}/images/{id}', [ProductImagesController::class, 'show']);
Route::put('products/{productId}/images/{id}/update', [ProductImagesController::class, 'update']);
Route::delete('products/{productId}/images/{id}/delete', [ProductImagesController::class, 'destroy']);


//Cart routes
Route::post('cart/add-to-cart', [CartController::class, 'addToCart']);
Route::post('cart/check-product', [CartController::class, 'checkProduct']);
Route::post('cart/update-quantity', [CartController::class, 'updateQuantity']);
Route::get('cart/get-cart', [CartController::class, 'getCart']);
Route::get('cart/count-items', [CartController::class, 'countItems']);
Route::delete('cart/remove-item/{cart_id}', [CartController::class, 'removeItem']);

//Place order routes
Route::post('place-order', [OrderController::class, 'placeOrder']);
Route::get('orders', [OrderController::class, 'index']);
Route::get('orders/history', [OrderController::class, 'getOrderHistory']);
Route::get('orders/history/{id}', [OrderController::class, 'getOrderDetails']);
Route::patch('orders/{id}/confirm', [OrderController::class, 'confirmOrder']);
Route::patch('orders/{id}/cancel', [OrderController::class, 'cancelOrder']);


//Income Tracker routes
Route::get('income', [IncomeTrackerController::class, 'getIncome']);
Route::get('order-stats', [IncomeTrackerController::class, 'getOrderStats']);
Route::get('export-orders', [IncomeTrackerController::class, 'exportOrders']);
