<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('products')->group(function () {
    Route::apiResource('/', ProductController::class)->except(['edit', 'create']);
});
