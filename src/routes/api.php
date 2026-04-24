<?php

use App\Http\Controllers\Api\RoomController;
use \App\Http\Controllers\Api\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('rooms', RoomController::class)
    ->parameters(['rooms' => 'id']);
Route::apiResource('bookings', BookingController::class)
    ->parameters(['bookings' => 'id']);
