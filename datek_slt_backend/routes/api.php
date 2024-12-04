<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AddressesController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductTypeController;
use App\Http\Controllers\Api\ProductImagesController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\BrandController;

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
Route::controller(AuthController::class)->group(function (){
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

//Brand routes
Route::get('brands', [BrandController::class, 'index']);

//Product routes
Route::get('products', [ProductController::class, 'index']);
Route::get('products/latest', [ProductController::class, 'getLatestProducts']);
Route::get('products/category', [ProductController::class, 'getProductsByCategory']);
Route::get('product/detail', [ProductController::class, 'show']);
Route::post('products/create', [ProductController::class, 'createProduct']);
Route::get('products/{id}/edit', [ProductController::class, 'edit']);
Route::put('products/{id}/update', [ProductController::class, 'update']);
Route::delete('products/{id}/delete', [ProductController::class, 'destroy']);

//ProductType routes
Route::get('product-types', [ProductTypeController::class, 'index']);
Route::post('product-types/create', [ProductTypeController::class, 'store']);
Route::get('product-types/{id}/edit', [ProductTypeController::class, 'edit']);
Route::put('product-types/{id}/update', [ProductTypeController::class, 'update']);
Route::delete('product-types/{id}/delete', [ProductTypeController::class, 'destroy']);

//Product Images routes
Route::get('products/{productId}/images', [ProductImagesController::class, 'index']);
Route::post('products/{productId}/images/create', [ProductImagesController::class, 'store']);
Route::get('products/{productId}/images/{id}', [ProductImagesController::class, 'show']);
Route::put('products/{productId}/images/{id}/update', [ProductImagesController::class, 'update']);
Route::delete('products/{productId}/images/{id}/delete', [ProductImagesController::class, 'destroy']);
