<?php

use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\API\V1\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);
Route::get("/logout", [AuthController::class, "logout"])->middleware("loggedIn");
Route::get("/logout-all", [AuthController::class, "logoutAllDevices"])->middleware("loggedIn");
