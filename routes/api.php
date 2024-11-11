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
	PaymentRequestController,
	FormController,
};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Enums\FormType;
use App\Enums\JournalStatus;
use App\Enums\VoucherStatus;
use App\Enums\FormStatus;


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
	Route::resource('journal-entry', JournalEntryController::class);
	Route::resource('payment-request', PaymentRequestController::class);

	Route::get('voucher/number/{prefix}', [VoucherController::class, 'voucherNo']);
	Route::get('payment-request/form/{prfNo}', [PaymentRequestController::class, 'prfNo']);

	Route::get('form-types', function(Request $request) {
		return response()->json([ 'forms' => FormType::cases() ], 200);
	});

	Route::prefix('form')->group(function () {

		Route::get('/status', function(Request $request) {
			return response()->json([ 'status' => FormStatus::cases() ], 200);
		});
		
		Route::put('/approved/{id}', [FormController::class, 'approved']);
		Route::put('/rejected/{id}', [FormController::class, 'rejected']);
		Route::put('/void/{id}', [FormController::class, 'void']);
		Route::put('/issued/{id}', [FormController::class, 'issued']);
	});

	Route::prefix('voucher')->group(function () {

		Route::get('/status', function(Request $request) {
			return response()->json([ 'status' => VoucherStatus::cases() ], 200);
		});

		Route::put('/completed/{id}', [VoucherController::class, 'completed']);
		Route::put('/approved/{id}', [VoucherController::class, 'approved']);
		Route::put('/rejected/{id}', [VoucherController::class, 'rejected']);
		Route::put('/void/{id}', [VoucherController::class, 'void']);
		Route::put('/issued/{id}', [VoucherController::class, 'issued']);

	});

	Route::prefix('journal-entry')->group(function () {

		Route::get('/status', function(Request $request) {
			return response()->json([ 'status' => JournalStatus::cases() ], 200);
		});

		Route::put('/post/{id}', [JournalEntryController::class, 'post']);
		Route::put('/open/{id}', [JournalEntryController::class, 'open']);
		Route::put('/void/{id}', [JournalEntryController::class, 'void']);
	});

});