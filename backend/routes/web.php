<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/relatorio-sql', [ReportController::class, 'index'])->name('report.index');

Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
