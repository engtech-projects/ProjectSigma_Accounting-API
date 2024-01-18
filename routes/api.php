<?php

use App\Http\Controllers\Api\v1\Auth\LoginController;
use App\Http\Controllers\Api\v1\User\UserController;
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

/* Route::get('/users',[UserController::class, 'index']);
Route::post('/auth/login',LoginController::class); */


Route::middleware('auth:api')->group(function() {
    Route::get('/user/{id}',[UserController::class, 'show']);
    Route::get('/users',[UserController::class, 'index']);
});
/* Route::middleware('auth:sanctum')->get('/user/{id}', function (Request $request) {
    return response()->json(['ams' => $request->user()]);
}); */

