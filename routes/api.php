<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VehicleDetectionController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:5,1')->post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/vehicle-detect', [VehicleDetectionController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
});
