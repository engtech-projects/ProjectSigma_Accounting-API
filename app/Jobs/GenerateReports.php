<?php

namespace App\Jobs;

use App\Enums\ReportType;
use App\Services\Reports\CashAdvanceSummaryService;
use App\Services\Reports\CashReturnSlipService;
use App\Services\Reports\ExpensesForTheMonthService;
use App\Services\Reports\IncomeStatementService;
use App\Services\Reports\LiquidationFormService;
use App\Services\Reports\MemorandumOfDepositService;
use App\Services\Reports\MonthlyProjectExpensesService;
use App\Services\Reports\OfficeCodeService;
use App\Services\Reports\OfficeHumanResourceService;
use App\Services\Reports\MonthlyUnliquidatedCashAdvanceService;
use App\Services\Reports\PayrollLiquidationService;
use App\Services\Reports\ProvisionalReceiptService;
use App\Services\Reports\ReplenishmentSummaryService;
use App\Services\Reports\StatementOfCashFlowService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use App\Services\Reports\BalanceSheetService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenerateReports implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected static $dateFrom;
    protected static $dateTo;
    protected static $type;
    public $timeout = 300;
    public $tries = 3;

    public function __construct(string $type, string $dateFrom, string $dateTo)
    {
        self::$type = $type;
        self::$dateFrom = Carbon::parse($dateFrom)->startOfDay();
        self::$dateTo =  Carbon::parse($dateTo)->startOfDay();
    }

    public function handle(): void
    {
        $startTime = now();
        $cacheKey = self::getCacheKey();
        $jobStatusKey = "job_processing_{$cacheKey}";

        //match the current report type
        try {
            $dataToCache = match (self::$type) {
                ReportType::BALANCE_SHEET->value => BalanceSheetService::balanceSheetReport(self::$dateFrom, self::$dateTo),
                ReportType::INCOME_STATEMENT->value => IncomeStatementService::incomeStatementReport(self::$dateFrom, self::$dateTo),
                ReportType::STATEMENT_CASH_FLOW->value => StatementOfCashFlowService::statementOfCashFlowReport(self::$dateFrom, self::$dateTo),
                ReportType::OFFICE_CODE->value =>  OfficeCodeService::officeCodeReport(self::$dateFrom, self::$dateTo),
                ReportType::OFFICE_HUMAN_RESOURCE->value =>  OfficeHumanResourceService::officeHumanResourceReport(self::$dateFrom, self::$dateTo),
                ReportType::MONTHLY_PROJECT_EXPENSES->value =>  MonthlyProjectExpensesService::monthlyProjectExpenseReport(self::$dateFrom, self::$dateTo),
                ReportType::MONTHLY_UNLIQUIDATED_CASH_ADVANCE->value =>  MonthlyUnliquidatedCashAdvanceService::monthlyUnliquidatedCashAdvanceReport(self::$dateFrom, self::$dateTo),
                ReportType::EXPENSES_FOR_THE_MONTH->value =>  ExpensesForTheMonthService::expensesForTheMonthReport(self::$dateFrom, self::$dateTo),
                ReportType::LIQUIDATION_FORM->value =>  LiquidationFormService::liquidationFormReport(self::$dateFrom, self::$dateTo),
                ReportType::REPLENISHMENT_SUMMARY->value =>  ReplenishmentSummaryService::replenishmentSummaryReport(self::$dateFrom, self::$dateTo),
                ReportType::CASH_ADVANCE_SUMMARY->value =>  CashAdvanceSummaryService::cashAdvanceSummaryReport(self::$dateFrom, self::$dateTo),
                ReportType::MEMORANDUM_OF_DEPOSIT->value =>  MemorandumOfDepositService::memorandumOfDepositReport(self::$dateFrom, self::$dateTo),
                ReportType::PROVISIONAL_RECEIPT->value =>  ProvisionalReceiptService::provisionalReceiptReport(self::$dateFrom, self::$dateTo),
                ReportType::CASH_RETURN_SLIP->value =>  CashReturnSlipService::cashReturnSlipReport(self::$dateFrom, self::$dateTo),
                ReportType::PAYROLL_LIQUIDATIONS->value =>  PayrollLiquidationService::payrollLiquidationReport(self::$dateFrom, self::$dateTo),
            };

            // Add metadata
            $dataToCache['generated_at'] = now()->toISOString();
            $dataToCache['generation_time_seconds'] = now()->diffInSeconds($startTime);
            Cache::put($cacheKey, $dataToCache, now()->addMinutes(1440));
            Cache::forget($jobStatusKey);
            Log::info(self::$type . " generated successfully", [
                'cache_key' => $cacheKey,
                'duration_seconds' => now()->diffInSeconds($startTime),
            ]);
        } catch (\Exception $e) {
            Cache::forget($jobStatusKey);
            Log::error("Failed to generate " . self::$type, [
                'error' => $e->getMessage(),
                'date_from' => self::$dateFrom,
                'date_to' => self::$dateTo,
            ]);
            throw $e;
        }
    }

    public static function getCacheKey(): string
    {
        return strtoupper(self::$type) . '_' . self::$dateFrom->format('Y_m_d') . '_to_' . self::$dateTo->format('Y_m_d');
    }
}
