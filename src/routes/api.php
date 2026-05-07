<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoomController;
use \App\Http\Controllers\Api\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('rooms', RoomController::class);
    Route::get('/rooms/available', [RoomController::class, 'getAvailableRooms']);
    Route::apiResource('bookings', BookingController::class);

    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
    Route::get('/user/bookings', [BookingController::class, 'userBookings']);

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
