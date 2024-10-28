<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserAddressesController;

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

Route::controller(UserController::class)->group(function () {
    Route::get('users', 'index');
    Route::post('users/create', 'store');
    Route::get('users/{id}/edit', 'edit');
    Route::put('users/{id}/update', 'update');
    Route::delete('users/{id}/delete', 'destroy');
});

 
Route::get('addresses', [UserAddressesController::class, 'index']);
Route::get('users/{user}/addresses', [UserAddressesController::class, 'getAddressesByUserId']);
Route::post('addresses/create', [UserAddressesController::class, 'store']);
Route::get('addresses/{id}/edit', [UserAddressesController::class, 'edit']);
Route::put('addresses/{id}/update', [UserAddressesController::class, 'update']);
Route::delete('addresses/{id}/delete', [UserAddressesController::class, 'destroy']);
Route::get('addresses/{id}', [UserAddressesController::class, 'show']);
