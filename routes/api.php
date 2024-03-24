<?php

use App\Http\Controllers\API\V1\AttributeController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\RatingController;
use App\Http\Controllers\API\V1\WishListController;
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

    Route::middleware('client')->group( function() {
        Route::apiResource("rating", RatingController::class , ['only' => ['store' , 'destroy']]);
        Route::apiResource("wishlist", WishListController::class , ['only' => ['index','store' , 'destroy']]);
    });
    
    Route::middleware('admin')->group( function() {
        Route::apiResource("category", CategoryController::class , ['except' => ['index', 'show']]);
        Route::get("category/show-sub-category", [CategoryController::class , 'show_sub_category']);
        Route::apiResource("product", ProductController::class , ['except' => ['index', 'show']]);
        
    });
    
    Route::middleware('hasAnyRole:client,admin')->group( function() {
        Route::apiResource("category", CategoryController::class , ['only' => ['index', 'show']]);
        Route::apiResource("product", ProductController::class , ['only' => ['index', 'show']]);
        Route::apiResource("rating", RatingController::class , ['only' => ['index']]);
        Route::apiResource("product/attribute", AttributeController::class)->only(['store', 'update' , 'destroy']);
    });
    
});


