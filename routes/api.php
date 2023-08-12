<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PersonalController;
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

Route::get('/profile-details', [AuthController::class, 'userDetails']);
Route::get('/logout', [AuthController::class, 'logout']);


Route::post('/login', [PersonalController::class, 'login']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/logout', [PersonalController::class, 'logout'])->middleware('auth:api');
