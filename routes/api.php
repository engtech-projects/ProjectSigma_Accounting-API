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
};

use App\Http\Controllers\Hrms\HrmsController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\ParticularGroupController;
use App\Http\Controllers\Projects\ProjectController;
use App\Http\Controllers\SyncController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Enums\FormType;
use App\Enums\JournalStatus;
use App\Enums\VoucherStatus;

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
    Route::get('form-types', function(Request $request) {
		return response()->json([ 'forms' => FormType::cases() ], 200);
	});
    Route::get('chart-of-accounts',[AccountsController::class, 'chartOfAccounts']);
    Route::resource('accounts', AccountsController::class);
    Route::resource('account-type', AccountTypeController::class);
	Route::resource('account-group', AccountGroupController::class);
	Route::resource('books', BookController::class);
	Route::resource('posting-period', PostingPeriodController::class);
	Route::resource('stakeholders', StakeHolderController::class);
    Route::resource('particular-group', ParticularGroupController::class);
    Route::prefix('journal-entry')->group(function () {
        Route::get('payment-request-entries', [PaymentRequestController::class, 'journalPaymentRequestEntries']);
        Route::get('unposted-entries', [JournalEntryController::class, 'unpostedEntries']);
        Route::get('posted-entries', [JournalEntryController::class, 'postedEntries']);
        Route::get('drafted-entries', [JournalEntryController::class, 'draftedEntries']);
        Route::get('generate-journal-number', [JournalEntryController::class, 'generateJournalNumber']);
        Route::resource('resource', JournalEntryController::class)->names('journal-entries');
        Route::get('status', function(Request $request) {
            return response()->json([ 'status' => JournalStatus::cases() ], 200);
        });
    });
    Route::resource('payment-request', PaymentRequestController::class);
    Route::prefix('npo')->group(function () {
        Route::resource('resource', PaymentRequestController::class)->names('npo.payment-requests');
        Route::get('my-requests',[PaymentRequestController::class, 'myRequest']);
        Route::get('my-approvals',[PaymentRequestController::class, 'myApprovals']);
    });

    //search routes
    Route::get('search-stakeholders', [PaymentRequestController::class, 'searchStakeHolders']);
    Route::get('search-particular-groups', [ParticularGroupController::class, 'searchParticularGroups']);
    Route::get('search-journal-accounts', [AccountsController::class, 'searchAccounts']);

	Route::prefix('vouchers')->group(function () {
        Route::prefix('disbursement')->group(function () {
            Route::post('create-voucher', [VoucherController::class, 'createDisbursement']);
            Route::get('all-list',[VoucherController::class, 'disbursementAllRequest']);
            Route::get('my-requests',[VoucherController::class, 'disbursementMyRequest']);
            Route::get('my-approvals',[VoucherController::class, 'disbursementMyApprovals']);
            Route::get('my-vouchering',[VoucherController::class, 'disbursementMyVouchering']);
        });
        Route::prefix('cash')->group(function () {
            Route::post('create-voucher', [VoucherController::class, 'createCash']);
            Route::get('all-list',[VoucherController::class, 'cashAllRequest']);
            Route::get('my-requests',[VoucherController::class, 'cashMyRequest']);
            Route::get('my-approvals',[VoucherController::class, 'cashMyApprovals']);
        });
        Route::get('number/{prefix}', [VoucherController::class, 'voucherNo']);
        Route::get('status', function(Request $request) {
            return response()->json([ 'status' => VoucherStatus::cases() ], 200);
        });
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
// SYSTEM SETUP ROUTES
if (config()->get('app.artisan') == 'true') {
    Route::prefix('artisan')->group(function () {
        Route::get('storage', function () {
            Artisan::call("storage:link");
            return "success";
        });
        Route::get('optimize', function () {
            Artisan::call("optimize");
            return "success";
        });
        Route::get('optimize-clear', function () {
            Artisan::call("optimize:clear");
            return "success";
        });
    });
}
