<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\WaterController;
use App\Http\Controllers\AIController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/ai/chat', [AIController::class, 'chat']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Meals
    Route::get('/meals', [MealController::class, 'index']);
    Route::post('/meals', [MealController::class, 'store']);
    Route::delete('/meals/{id}', [MealController::class, 'destroy']);
    Route::post('/meals/clear', [MealController::class, 'clear']);

    // Water
    Route::get('/water', [WaterController::class, 'show']);
    Route::post('/water', [WaterController::class, 'store']);
});
