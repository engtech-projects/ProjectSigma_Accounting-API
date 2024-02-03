<?php

use App\Http\Controllers\Api\v1\AccountCategoryController;
use App\Http\Controllers\Api\v1\AccountTypeController;
use App\Http\Controllers\Api\v1\AccountController;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\ChartOfAccountController;
use App\Http\Controllers\Api\v1\DashboardController;
use App\Http\Controllers\Api\v1\JournalBookController;
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


Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('/user', [AuthController::class, 'show']);
    });
/*     Route::prefix('dashboard')->group(function () {
        Route::get('', [DashboardController::class, 'index']);
    }); */

    Route::get('dashboard',DashboardController::class);

    Route::get('chart-of-accounts',ChartOfAccountController::class);
    Route::resource('accounts', AccountController::class);
    Route::prefix('account')->group(function () {
        Route::resource('type', AccountTypeController::class);
        Route::resource('category', AccountCategoryController::class);
    });
    Route::prefix('journal')->group(function () {
        Route::resource('book',JournalBookController::class);
    });

});

