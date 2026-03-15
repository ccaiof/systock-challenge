<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::post("/register", [AuthController::class, "register"])->name("auth.register");
