<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('user', UserController::class);

Route::resource('products', ProductController::class);

/////////PASSPORT/////////

Route::post('users/register', [UserController::class, 'register']);

Route::post('users/login', [UserController::class, 'login']);

Route::get('users/login', [UserController::class, 'login'])->name('login');

Route::middleware('auth:api')->resource('products', ProductController::class);

Route::middleware('auth:api')->group(function () {
    Route::post('users/logout', [UserController::class, 'logout']);
});
