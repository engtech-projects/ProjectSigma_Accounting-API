<?php

use App\Enums\AccountCategory;
use App\Enums\BalanceType;
use App\Enums\FormType;
use App\Enums\JournalStatus;
use App\Enums\RequestStatuses;
use App\Enums\StakeHolderType;
use App\Http\Controllers\AccountGroupController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\Actions\Approvals\ApproveApproval;
use App\Http\Controllers\Actions\Approvals\DisapproveApproval;
use App\Http\Controllers\Actions\Approvals\VoidApproval;
use App\Http\Controllers\APiSyncController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\ParticularGroupController;
use App\Http\Controllers\PaymentRequestController;
use App\Http\Controllers\PayrollRequestController;
use App\Http\Controllers\PostingPeriodController;
use App\Http\Controllers\PostingPeriodDetailsController;
use App\Http\Controllers\ReportGroupController;
use App\Http\Controllers\StakeHolderController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\TransactionFLowModelController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\WithHoldingTaxController;
use App\Models\WithHoldingTax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    // RAW RESPONSE ROUTES BASED ON ENUMS
    Route::get('vat-value', function (Request $request) {
        return response()->json(['vat' => config('services.vat.value')], 200);
    });
    Route::get('account-category', function (Request $request) {
        return response()->json(['account_category' => AccountCategory::cases()], 200);
    });
    Route::get('balance-type', function (Request $request) {
        return response()->json(['balance_type' => BalanceType::cases()], 200);
    });
    Route::get('stakeholder-type', function (Request $request) {
        return response()->json(['stakeholder_type' => StakeHolderType::cases()], 200);
    });
    Route::get('form-types', function (Request $request) {
        return response()->json(['forms' => FormType::cases()], 200);
    });
    Route::get('get-all-withholding-tax', function (Request $request) {
        return response()->json(['data' => WithHoldingTax::pluck('wtax_name', 'id')], 200);
    });
    // CUSTOM ROUTES
    Route::get('chart-of-accounts', [AccountsController::class, 'chartOfAccounts']);
    Route::resource('transaction-flow-model', TransactionFLowModelController::class);
    Route::post('update-transaction-flow', [TransactionFLowModelController::class, 'update']);

    // RESORCE ROUTES
    Route::resource('accounts', AccountsController::class);
    Route::resource('account-type', AccountTypeController::class);
    Route::resource('account-group', AccountGroupController::class);
    Route::resource('books', BookController::class);
    Route::resource('fiscalYear', PostingPeriodController::class);
    Route::resource('posting-period', PostingPeriodDetailsController::class);
    Route::resource('stakeholders', StakeHolderController::class);
    Route::resource('particular-group', ParticularGroupController::class);
    Route::resource('payment-request', PaymentRequestController::class);
    Route::resource('terms', TermController::class);
    Route::resource('report-group', ReportGroupController::class);
    Route::resource('withholding-tax', WithHoldingTaxController::class);

    // JOURNAL ENTRY ROUTES
    Route::prefix('journal-entry')->group(function () {
        Route::get('payment-request-entries', [PaymentRequestController::class, 'journalPaymentRequestEntries']);
        Route::get('unposted-entries', [JournalEntryController::class, 'unpostedEntries']);
        Route::get('posted-entries', [JournalEntryController::class, 'postedEntries']);
        Route::get('open-entries', [JournalEntryController::class, 'openEntries']);
        Route::get('void-entries', [JournalEntryController::class, 'voidEntries']);
        Route::get('disbursement-entries', [JournalEntryController::class, 'disbursementEntries']);
        Route::get('for-payment-entries', [JournalEntryController::class, 'forPaymentEntries']);
        Route::get('cash-entries', [JournalEntryController::class, 'cashEntries']);
        Route::get('get-accounts-vat-tax', [JournalEntryController::class, 'getAccountsVatTax']);

        Route::get('generate-journal-number', [JournalEntryController::class, 'generateJournalNumber']);
        Route::post('generate-journal-details', [JournalEntryController::class, 'generateJournalDetails']);
        Route::get('for-voucher-entries-disbursement', [JournalEntryController::class, 'forVoucherEntriesListDisbursement']);
        Route::get('for-voucher-entries-cash', [JournalEntryController::class, 'forVoucherEntriesListCash']);
        Route::resource('resource', JournalEntryController::class)->names('journal-entries');
        Route::get('status', function (Request $request) {
            return response()->json(['status' => JournalStatus::cases()], 200);
        });
    });

    // NON-PURCHASE ORDER ROUTES
    Route::prefix('npo')->group(function () {
        Route::resource('resource', PaymentRequestController::class)->names('npo.payment-requests');
        Route::get('my-requests', [PaymentRequestController::class, 'myRequest']);
        Route::get('my-approvals', [PaymentRequestController::class, 'myApprovals']);
        Route::get('my-denied-requests', [PaymentRequestController::class, 'myDeniedRequests']);
        Route::get('generate-prf-no', [PaymentRequestController::class, 'generatePrfNo']);
        Route::post('upload-attachment', [PaymentRequestController::class, 'uploadAttachment']);
    });

    // PAYROLL ROUTES
    Route::prefix('payroll')->group(function () {
        Route::resource('resource', PayrollRequestController::class)->names('payroll.payment-requests');
        Route::post('create-request', [PayrollRequestController::class, 'createPayrollRequest']);
    });

    // VOUCHERS ROUTES
    Route::prefix('vouchers')->group(function () {
        Route::prefix('disbursement')->group(function () {
            Route::resource('resource', VoucherController::class)->names('vouchers.disbursement');
            Route::post('create-voucher', [VoucherController::class, 'createDisbursement']);
            Route::get('all-list', [VoucherController::class, 'disbursementAllRequest']);
            Route::get('my-requests', [VoucherController::class, 'disbursementMyRequest']);
            Route::get('my-approvals', [VoucherController::class, 'disbursementMyApprovals']);
            Route::get('my-vouchering', [VoucherController::class, 'disbursementMyVouchering']);
            Route::get('generate-number', [VoucherController::class, 'disbursementGenerateVoucherNumber']);
        });
        Route::prefix('cash')->group(function () {
            Route::resource('resource', VoucherController::class)->names('vouchers.cash');
            Route::post('create-voucher', [VoucherController::class, 'createCash']);
            Route::post('received', [VoucherController::class, 'cashReceived']);
            Route::get('all-list', [VoucherController::class, 'cashAllRequest']);
            Route::get('my-requests', [VoucherController::class, 'cashMyRequest']);
            Route::get('my-approvals', [VoucherController::class, 'cashMyApprovals']);
            Route::get('my-vouchering', [VoucherController::class, 'cashMyVouchering']);
            Route::get('get-clearing-vouchers', [VoucherController::class, 'cashGetClearingVouchers']);
            Route::get('get-cleared-vouchers', [VoucherController::class, 'cashGetClearedVouchers']);
            Route::get('generate-number', [VoucherController::class, 'cashGenerateVoucherNumber']);
        });
        Route::get('status', function (Request $request) {
            return response()->json(['status' => RequestStatuses::cases()], 200);
        });
    });

    // SYNCHRONIZATION ROUTES
    Route::prefix('sync')->group(function () {
        Route::prefix('hrms')->group(function () {
            Route::post('/all', [APiSyncController::class, 'syncAll']);
            Route::post('/employee', [APiSyncController::class, 'syncEmployees']);
            Route::post('/department', [APiSyncController::class, 'syncDepartments']);
            Route::post('/users', [APiSyncController::class, 'syncUsers']);
        });
        Route::prefix('project')->group(function () {
            Route::post('/all', [APiSyncController::class, 'syncAll']);
            Route::post('/project', [APiSyncController::class, 'syncProjects']);
        });
        Route::prefix('inventory')->group(function () {
            Route::post('/all', [APiSyncController::class, 'syncAll']);
            Route::post('/supplier', [APiSyncController::class, 'syncSuppliers']);
        });
    });

    // APPROVALS ROUTES
    Route::prefix('approvals')->group(function () {
        Route::post('approve/{modelName}/{model}', ApproveApproval::class);
        Route::post('disapprove/{modelName}/{model}', DisapproveApproval::class);
        Route::post('void/{modelName}/{model}', VoidApproval::class);
    });
});

// SECRET API KEY ROUTES
Route::middleware('secret_api')->group(function () {
    // SIGMA SERVICES ROUTES
    Route::prefix('sigma')->group(function () {
        Route::prefix('payroll')->group(function () {
            Route::post('create-request', [PayrollRequestController::class, 'createPayrollRequest']);
        });
    });
});

// SEARCH ROUTES
Route::get('search-stakeholders', [PaymentRequestController::class, 'searchStakeHolders']);
Route::get('search-particular-groups', [ParticularGroupController::class, 'searchParticularGroups']);
Route::get('search-journal-accounts', [AccountsController::class, 'searchAccounts']);
Route::get('search-report-groups', [ReportGroupController::class, 'searchReportGroups']);

// SYSTEM SETUP ROUTES
if (config()->get('app.artisan') == 'true') {
    Route::prefix('artisan')->group(function () {
        Route::get('storage', function () {
            Artisan::call('storage:link');

            return 'success';
        });
        Route::get('optimize', function () {
            Artisan::call('optimize');

            return 'success';
        });
        Route::get('optimize-clear', function () {
            Artisan::call('optimize:clear');

            return 'success';
        });
    });
}
