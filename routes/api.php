<?php

use App\Enums\AccountCategory;
use App\Enums\BalanceType;
use App\Enums\StakeHolderType;
use App\Http\Controllers\Actions\Approvals\ApproveApproval;
use App\Http\Controllers\Actions\Approvals\DisapproveApproval;
use App\Http\Controllers\Actions\Approvals\VoidApproval;
use App\Http\Controllers\{
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

use App\Http\Controllers\Hrms\HrmsController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Projects\ProjectController;
use App\Http\Controllers\SyncController;
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
    return Auth()->user();
});

Route::middleware('auth:api')->group(function () {
    Route::get('vat-value', function(Request $request) {
        return response()->json([ 'vat' => config('services.vat.value') ], 200);
    });
    Route::get('account-category', function(Request $request) {
        return response()->json([ 'account_category' => AccountCategory::cases() ], 200);
    });
    Route::get('balance-type', function(Request $request) {
        return response()->json([ 'balance_type' => BalanceType::cases() ], 200);
    });
    Route::get('stakeholder-type', function(Request $request) {
        return response()->json([ 'stakeholder_type' => StakeHolderType::cases() ], 200);
    });
    Route::resource('accounts', AccountsController::class);
    Route::resource('account-type', AccountTypeController::class);
	Route::resource('account-group', AccountGroupController::class);
	Route::resource('books', BookController::class);
	Route::resource('posting-period', PostingPeriodController::class);
	Route::resource('stakeholders', StakeHolderController::class);
	Route::resource('voucher', VoucherController::class);
	Route::resource('journal-entry', JournalEntryController::class);
    Route::resource('payment-request', PaymentRequestController::class);
    Route::prefix('npo')->group(function () {
        Route::resource('resource',PaymentRequestController::class);
        Route::get('my-requests',[PaymentRequestController::class, 'myRequest']);
        Route::get('my-approvals',[PaymentRequestController::class, 'myApprovals']);
    });
    Route::get('search-stakeholders', [PaymentRequestController::class, 'searchStakeHolders']);
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

    Route::prefix('sync')->group(function () {
        Route::post('/all', [SyncController::class, 'syncAll']);
        Route::prefix('hrms')->group(function () {
            Route::post('/all', [HrmsController::class, 'syncAll']);
            Route::post('/employee', [HrmsController::class, 'syncEmployee']);
            Route::post('/department', [HrmsController::class, 'syncDepartment']);
            Route::post('/users', [HrmsController::class, 'syncUsers']);
        });
        Route::prefix('project')->group(function () {
            Route::post('/all', [ProjectController::class, 'syncAll']);
            Route::post('/project', [ProjectController::class, 'syncProject']);
        });
        Route::prefix('inventory')->group(function () {
            Route::post('/all', [InventoryController::class, 'syncAll']);
            Route::post('/supplier', [InventoryController::class, 'syncSupplier']);
        });
    });
    Route::prefix('approvals')->group(function () {
        Route::post('approve/{modelName}/{model}', ApproveApproval::class);
        Route::post('disapprove/{modelName}/{model}', DisapproveApproval::class);
        Route::post('void/{modelName}/{model}', VoidApproval::class);
    });
});
