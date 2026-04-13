<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('login', LoginController::class)->name('login');
    Route::post('register', RegisterController::class)->name('register');
});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('logout', LogoutController::class)->name('logout');
    });

    Route::get('whoami', AuthController::class)->name('whoami');

});
