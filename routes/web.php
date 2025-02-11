<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;

Route::get('/', function () {
    return view('home');
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'Callback'])->name('callback');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');