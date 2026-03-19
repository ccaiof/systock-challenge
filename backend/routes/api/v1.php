<?php

use App\Http\Controllers\Api\V1\ProductsController;
use App\Http\Controllers\Api\V1\UsersController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UsersController::class);
    Route::apiResource('products', ProductsController::class);
});
