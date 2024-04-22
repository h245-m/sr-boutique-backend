<?php

use App\Http\Controllers\API\V1\AttributeController;
use App\Http\Controllers\API\V1\CartController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\MessageController;
use App\Http\Controllers\API\V1\OrderController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\RatingController;
use App\Http\Controllers\API\V1\WishListController;
use App\Http\Controllers\API\V1\UserController;
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

Route::middleware('loggedIn')->group( function() {

    Route::patch("/user/update", [UserController::class, "update"]);
    Route::apiResource("message", MessageController::class);

    Route::middleware('client')->group( function() {
        Route::apiResource("rating", RatingController::class , ['only' => ['store' , 'destroy']]);
        Route::apiResource("wishlist", WishListController::class , ['only' => ['index','store' ,'destroy']]);
        Route::get("/cart/my-cart", [CartController::class, "show"]);
        Route::post("/cart/updateCart" , [WishListController::class, "update"]);
        Route::get("/cart/add-to-cart", [CartController::class, "store"]);
        Route::delete("/cart/remove-from-cart", [CartController::class, "destroy"]);
        Route::post("/order/my_order", [OrderController::class, "my_order"]);
        Route::apiResource("order", OrderController::class , ['only' => ['store']]);
    });
    
    Route::middleware('admin')->group( function() {
        Route::get("category/show-admin", [CategoryController::class , 'show_admin' ]);
        Route::get("product/index_admin", [ProductController::class , 'index_admin' ]);
        Route::apiResource("category", CategoryController::class , ['except' => ['index', 'show']]);
        Route::apiResource("product", ProductController::class , ['except' => ['index', 'show']]);
        Route::apiResource("order", OrderController::class , ['only' => ['update', 'index']]);
        Route::apiResource("product/attribute", AttributeController::class)->only(['store', 'update' , 'destroy']);
    });
    
    Route::middleware('hasAnyRole:client,admin')->group( function() {
        Route::apiResource("order", OrderController::class , ['only' => ['show']]);
    });
    

});

Route::apiResource("product", ProductController::class , ['only' => ['index', 'show']]);
Route::apiResource("category", CategoryController::class , ['only' => ['index', 'show']]);
Route::apiResource("rating", RatingController::class , ['only' => ['index']]);
Route::get("category/show-sub-category", [CategoryController::class , 'show_sub_category']);


