<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;



// Authentication Routes
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});


// User Routes
Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::get('user/all', 'index');
    Route::get('show/{user}', 'show');
    Route::post('store', 'store');
    Route::put('update/{user}', 'update');
    Route::delete('{user}', 'destroy');
});
