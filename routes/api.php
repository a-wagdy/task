<?php

use App\Http\Controllers\API\ReportController;
use Illuminate\Support\Facades\Route;


Route::get('/report', [ReportController::class, 'index'])->middleware('api');
Route::get('/report/{symbol}', [ReportController::class, 'show'])->where('symbol', '[A-Za-z]+')->middleware('api');