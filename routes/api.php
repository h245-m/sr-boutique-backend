<?php

use App\Http\Controllers\API\V1\CartController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\MessageController;
use App\Http\Controllers\API\V1\OrderController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\RatingController;
use App\Http\Controllers\API\V1\WishListController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\ShippingController;
use App\Http\Controllers\API\V1\StatsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth Routes are in auth.php


Route::get("category/show-sub-category", [CategoryController::class , 'show_sub_category']);

Route::middleware('loggedIn')->group( function() {

    Route::post("user/update_profile", [UserController::class , 'update_profile']);

    Route::middleware('client')->group( function() {
        Route::apiResource("rating", RatingController::class , ['only' => ['store' , 'destroy']]);
        Route::apiResource("wishlist", WishListController::class , ['only' => ['index','store' ,'destroy']]);
        Route::get("/cart/my-cart", [CartController::class, "show"]);
        Route::post("/cart/updateCart" , [WishListController::class, "update"]);
        Route::get("/cart/add-to-cart", [CartController::class, "store"]);
        Route::delete("/cart/remove-from-cart", [CartController::class, "destroy"]);
        Route::post("/order/my_order", [OrderController::class, "my_order"]);
        Route::apiResource("order", OrderController::class , ['only' => ['store' , 'destroy']]);
        Route::get("/message/send_as_client", [MessageController::class, "send_as_client"]);
    });

    Route::get("message/index_chat_messages", [MessageController::class,"index_chat_messages"]);
    
    Route::middleware('hasAnyRole:client,order,super_admin')->group( function() {
        Route::apiResource("order", OrderController::class , ['only' => ['show']]);
    });

    Route::middleware('hasAnyRole:over_view,super_admin')->group( function() {
        Route::get("stats", [StatsController::class , 'index']);
    });
    
    Route::middleware('hasAnyRole:category,super_admin')->group( function() {
        Route::apiResource("category", CategoryController::class , ['except' => ['index', 'show']]);
        Route::get("category/show-admin", [CategoryController::class , 'show_admin' ]);
    });

    Route::middleware('hasAnyRole:order,super_admin')->group( function() {
        Route::apiResource("order", OrderController::class , ['only' => ['index' , 'update']]);
    });

    Route::middleware('hasAnyRole:product,stock,super_admin')->group( function() {
        Route::apiResource("product", ProductController::class , ['except' => ['index', 'show']]);
        Route::get("product/index_admin", [ProductController::class , 'index_admin' ]);
    });

    Route::middleware('hasAnyRole:admin,super_admin')->group( function() {
        Route::apiResource("user", UserController::class);
    });

    Route::middleware('hasAnyRole:message')->group( function() {
        Route::apiResource("message", MessageController::class)->only(['index', 'store']);
        Route::get("message/index_chat_messages", [MessageController::class,"index_chat_messages"]);
    });

    Route::middleware('hasAnyRole:shipping,super_admin')->group( function() {
        Route::apiResource("shipping", ShippingController::class , ['only' => [ 'store' , 'update', 'destroy']]);
    });

    Route::middleware('hasAnyRole:setting,super_admin')->group( function() {
        // Route::apiResource("order", OrderController::class , ['only' => ['show']]);
    });
    

});

Route::apiResource("shipping", ShippingController::class , ['only' => [ 'index']]);
Route::apiResource("product", ProductController::class , ['only' => ['index', 'show']]);
Route::apiResource("category", CategoryController::class , ['only' => ['index', 'show']]);
Route::apiResource("rating", RatingController::class , ['only' => ['index' , 'update']]);



