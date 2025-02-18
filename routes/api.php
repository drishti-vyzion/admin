<?php

use App\Http\Controllers\AddItermController;
use App\Http\Controllers\AddressControllers;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemVarientController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\TokenMiddleware;
use App\Http\Resources\ItemListResource;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']); //  No Middleware
Route::post('/login', [AuthController::class, 'login']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/item', [ItemController::class, 'title']);
Route::post('/addresses', [AddressControllers::class, 'store']);
// Route::post('/wishlist/add',[AddItermController::class,'add_to_wishlist']);

Route::middleware([TokenMiddleware::class, 'auth:sanctum'])->group(function () {
    Route::get('/index', [AuthController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/categories', [CategoryController::class, 'index']); // List all items
    Route::get('/items', [ItemController::class, 'index']);
    Route::get('/items/{id}', [ItemController::class, 'show']); // Show single item

    Route::get('/item/varients', [ItemVarientController::class, 'index']);
    Route::post('/item/varients', [ItemVarientController::class, 'store']);

    Route::middleware('role:user')->group(function () {

        Route::post('/likes', [LikeController::class, 'store']);
        Route::get('/likes', [LikeController::class, 'index']);
        Route::get('/likes/{id}', [LikeController::class, 'show']);
        Route::delete('/likes/{id}', [LikeController::class, 'destroy']);

        Route::get('/cards', [AddItermController::class, 'index']);
        Route::post('/cards', [AddItermController::class, 'store']);
        Route::delete('/cards/{id}', [AddItermController::class, 'destroy']);

        Route::post('/orders', [OrderController::class, 'buyNow']);
        Route::post('/card/orders', [OrderController::class, 'checkout']);
        Route::post('/card/order', [OrderController::class, 'store']);

    });


    Route::middleware('role:retailer')->group(function () {
        Route::post('/items', [ItemController::class, 'store']);
        Route::put('/items/{id}', [ItemController::class, 'update']);
        Route::delete('/items/{id}', [ItemController::class, 'destroy']);
    });

    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'Callback'])->name('callback');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('auth')->name('dashboard');
    Route::get('/', function () {
        return view('home');
    });
});
