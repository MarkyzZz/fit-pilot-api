<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResendVerificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
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

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware('signed')
    ->name('user.verify');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('logout', LogoutController::class)->name('logout');
        Route::post('email/resend', ResendVerificationController::class)->middleware('throttle:3,5')->name('email.resend');
    });

    Route::get('whoami', AuthController::class)->name('whoami');

});
