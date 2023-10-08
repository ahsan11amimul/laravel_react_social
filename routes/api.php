<?php

use App\Http\Controllers\API\Authcontroller;
use App\Http\Controllers\API\PostController;
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
Route::post('/login', [Authcontroller::class, 'login']);
Route::post('/register', [Authcontroller::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [Authcontroller::class, 'logout']);
    Route::apiResource('posts', PostController::class);
});
