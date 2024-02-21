<?php

use App\Http\Controllers\Api\v1\{
    AccountTypeController,
    AccountController,
    SubsidiaryController,
    BookController,
    ChartOfAccountController,
    DashboardController,
    PostingPeriodController,
    TransactionTypeController,
    DocumentSeriesController,
    AccountGroupController
};

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\StakeHolderGroupController;
use App\Notifications\UserNotificationTest;
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
    Route::get('dashboard', DashboardController::class);

    Route::get('chart-of-accounts', ChartOfAccountController::class);
    Route::resource('accounts', AccountController::class);
    Route::prefix('account')->group(function () {
        Route::resource('type', AccountTypeController::class);
    });
    Route::resource('book', BookController::class);
    Route::resource('posting-period', PostingPeriodController::class);

    Route::resource('transaction-type', TransactionTypeController::class);
    Route::resource('document-series', DocumentSeriesController::class);
    Route::resource('subsidiary', SubsidiaryController::class);

    Route::resource('account-group', AccountGroupController::class);

    Route::resource('stakeholder-group', StakeHolderGroupController::class);

    Route::post('/test-event', function () {
        try {
            Notification::send(auth()->user(), new UserNotificationTest("Hello, this is notification."));
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return "test event";
    });


});

