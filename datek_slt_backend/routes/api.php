<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserAddressesController;
use App\Http\Controllers\Api\CategoryController;

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

// User routes
Route::controller(UserController::class)->group(function () {
    Route::get('users', 'index');
    Route::post('users/create', 'store');
    Route::get('users/{id}/edit', 'edit');
    Route::put('users/{id}/update', 'update');
    Route::delete('users/{id}/delete', 'destroy');
});


// Address routes
Route::get('addresses', [UserAddressesController::class, 'index']);
Route::get('users/{user}/addresses', [UserAddressesController::class, 'getAddressesByUserId']);
Route::post('addresses/create', [UserAddressesController::class, 'store']);
Route::get('addresses/{id}/edit', [UserAddressesController::class, 'edit']);
Route::put('addresses/{id}/update', [UserAddressesController::class, 'update']);
Route::delete('addresses/{id}/delete', [UserAddressesController::class, 'destroy']);
Route::get('addresses/{id}', [UserAddressesController::class, 'show']);

// Category routes
Route::get('categories', [CategoryController::class, 'index']);
Route::post('categories/create', [CategoryController::class, 'store']);
Route::get('categories/{id}/edit', [CategoryController::class, 'edit']);
Route::put('categories/{id}/update', [CategoryController::class, 'update']);
Route::delete('categories/{id}/delete', [CategoryController::class, 'destroy']);