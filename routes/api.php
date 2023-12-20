<?php

use App\Http\Controllers\AdminsAuthController;
use App\Http\Controllers\AdminsProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductsController;
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
    Route::get('', [ProductsController::class, 'getAllProducts']);
    Route::get('{id}', [ProductsController::class, 'getOneProduct']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('favorites/show', [ProductsController::class, 'getFavorites']);
        Route::delete('favorites/{id}', [ProductsController::class, 'removeFavorite']);
        Route::post('favorites', [ProductsController::class, 'addFavorite']);
    });
});

// Cart routes
Route::prefix('cart')->middleware('auth:sanctum')->group(function () {
    Route::get('', [CartController::class, 'getItems']);
    Route::post('add', [CartController::class, 'addItem']);
    Route::put('update/{id}', [CartController::class, 'updateItem']);
    Route::delete('delete/{id}', [CartController::class, 'deleteItem']);
    Route::post('reset/{id}', [CartController::class, 'resetCart']);
});

##############################################################################
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
        Route::get('{id}', [AdminsProductController::class, 'GetOneProduct']);
        Route::post('', [AdminsProductController::class, 'addProduct']);
        Route::put('{id}', [AdminsProductController::class, 'editProduct']);
        Route::delete('{id}', [AdminsProductController::class, 'deleteProduct']);
    });
});
