<?php

use App\Http\Controllers\BrandController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleModelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('client', ClientController::class);
Route::apiResource('vehicle', VehicleController::class);
Route::apiResource('location', LocationController::class);
Route::apiResource('brand', BrandController::class);
Route::apiResource('vehicle_model', VehicleModelController::class);