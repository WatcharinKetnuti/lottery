<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LotteryController;

Route::get('/', [LotteryController::class, 'index']);
Route::post('/generate', [LotteryController::class, 'generate']);
Route::post('/check', [LotteryController::class, 'check']);
Route::post('/clear', [LotteryController::class, 'clearSession']);