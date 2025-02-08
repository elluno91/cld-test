<?php

use App\Http\Controllers\API\AuthenticateController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\RoomController;
use App\Http\Middleware\CheckJWT;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([CheckJWT::class])->prefix("api")->group(function () {
    Route::get('/', function () {
        // ...
    });
    Route::post("/authenticate", [AuthenticateController::class, "authenticate"])->name("authenticate");
    Route::get("/customer", [CustomerController::class, "index"])->name("customer");
    Route::get("/room", [RoomController::class, "index"])->name("room");
    Route::get("/reservation", [ReservationController::class, "index"])->name("reservation");
    Route::post("/cancel-reservation", [ReservationController::class, "cancel"])->name("reservation.cancel");
    Route::post("/create-reservation", [ReservationController::class, "store"])->name("reservation.store");
});
