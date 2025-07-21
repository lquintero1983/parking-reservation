<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;

Route::post('/quote', [QuoteController::class, 'getQuote']);
Route::post('/reservation', [ReservationController::class, 'store']);
Route::get('/reservations', [ReservationController::class, 'index']);
Route::post('/reservation/{id}/cancel', [ReservationController::class, 'cancel']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);