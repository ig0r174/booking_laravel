<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("register", [UserController::class, "register"]);
Route::post("login", [UserController::class, "login"]);
Route::group(["middleware" => ["auth:api"]], function(){
    Route::get("profile/{id}/bookings", [BookingController::class, "userBookings"])->where(['id' => '\d+']);
    Route::get("logout", [UserController::class, "logout"]);

    Route::resource('booking', BookingController::class)->except(['edit']);
});
