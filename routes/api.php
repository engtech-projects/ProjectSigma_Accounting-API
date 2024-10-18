<?php

use App\Http\Controllers\Api\v1\{
    AccountTypeController,
    AccountsController,
    PostingPeriodController,
	VoucherController,
	BookController,
	StakeHolderController,
	AccountGroupController,
	JournalEntryController,
};

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

Route::middleware('auth:api')->get('user', function (Request $request) {
    // Route::get('/user', function (Request $request) {
		return Auth()->user();
	// });
});

Route::middleware('auth:api')->group(function () {
	Route::resource('accounts', AccountsController::class);
	Route::resource('account-group', AccountGroupController::class);
	Route::resource('books', BookController::class);
	Route::resource('posting-period', PostingPeriodController::class);
	Route::resource('stakeholders', StakeHolderController::class);
	Route::resource('voucher', VoucherController::class);
	Route::resource('journal', JournalEntryController::class);

	Route::get('voucher/number/{prefix}', [VoucherController::class, 'voucherNo']);
});


