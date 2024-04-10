<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
});



$dev_path = __DIR__ . '../Developers/';

Route::prefix('v1')->group(function () use ($dev_path) {

    // User Routes
    include "{$dev_path}Users.php";

    // Products routes
    include "{$dev_path}Products.php";
});
