<?php

use App\Http\Controllers\AdminsAuthController;
use App\Http\Controllers\AdminsCategoryController;
use App\Http\Controllers\AdminsColorController;
use App\Http\Controllers\AdminsProductController;
use App\Http\Controllers\UsersProductController;
use App\Http\Controllers\UsersAuthController;
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

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('login', [UsersAuthController::class, 'login']);
    Route::post('register', [UsersAuthController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UsersAuthController::class, 'logout']);
        Route::post('password/change', [UsersAuthController::class, 'changePassword']);
    });
});

// Products routes
Route::prefix('products')->group(function () {
    Route::get('', [UsersProductController::class, 'getAllProducts']);
    Route::get('{id}', [UsersProductController::class, 'getOneProduct']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('favorites/show', [UsersProductController::class, 'getFavorites']);
        Route::delete('favorites/{id}', [UsersProductController::class, 'removeFavorite']);
        Route::post('favorites', [UsersProductController::class, 'addFavorite']);
    });
});

##############################################################################
#########################  ADMIN ROUTES ######################################
##############################################################################

/**
 * Admin Routes
 */

Route::prefix('admin')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('login', [AdminsAuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AdminsAuthController::class, 'logout']);
            Route::post('password/change', [AdminsAuthController::class, 'changePassword']);
        });
    });

    // Product routes
    Route::prefix('products')->middleware('auth:sanctum')->group(function () {
        Route::get('', [AdminsProductController::class, 'GetAllProducts']);
        Route::get('{product}/variations', [AdminsProductController::class, 'getAllProductVariations']);
        Route::get('{id}', [AdminsProductController::class, 'GetOneProduct']);
        Route::post('', [AdminsProductController::class, 'addProduct']);
        Route::put('{id}', [AdminsProductController::class, 'editProduct']);
        Route::delete('{id}', [AdminsProductController::class, 'deleteProduct']);

        Route::prefix('variation')->group(function () {
            Route::post('', [AdminsProductController::class, 'addProductVariation']);
            Route::put('{variation}', [AdminsProductController::class, 'editProductVariation']);
            Route::delete('{variation}', [AdminsProductController::class, 'deleteProductVariation']);
        });
    });

    // Category routes
    Route::prefix('categories')->middleware('auth:sanctum')->group(function () {
        Route::get('', [AdminsCategoryController::class, 'getAllCategories']);
        Route::get('{category}', [AdminsCategoryController::class, 'getOneCategory']);
        Route::post('', [AdminsCategoryController::class, 'addCategory']);
        Route::put('{category}', [AdminsCategoryController::class, 'editCategory']);
        Route::delete('{category}', [AdminsCategoryController::class, 'deleteCategory']);
    });

    // Color routes
    Route::prefix('colors')->middleware('auth:sanctum')->group(function () {
        Route::get('', [AdminsColorController::class, 'getAllColors']);
        Route::get('{color}', [AdminsColorController::class, 'getOneColor']);
        Route::post('', [AdminsColorController::class, 'addColor']);
        Route::put('{color}', [AdminsColorController::class, 'editColor']);
        Route::delete('{color}', [AdminsColorController::class, 'deleteColor']);
    });
});
