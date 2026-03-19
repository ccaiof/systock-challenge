<?php

use App\Http\Controllers\Api\V1\ProductsController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('users/{user}/products', [ProductsController::class, 'indexByUser'])->name('users.products.index');
    Route::post('users/{user}/products', [ProductsController::class, 'storeByUser'])->name('users.products.store');
    Route::apiResource('users', UsersController::class);
    Route::apiResource('products', ProductsController::class);
});
