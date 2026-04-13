<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VehicleDetectionController;

Route::middleware('auth:sanctum')->post('/vehicle-detect', [VehicleDetectionController::class, 'store']);
