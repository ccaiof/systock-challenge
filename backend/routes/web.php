<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/relatorio-sql', [ReportController::class, 'index'])->name('report.index');
