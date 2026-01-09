<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ReportType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\OfficeExpenseFilter;
use App\Jobs\GenerateReports;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\JsonResponse;

class OfficeExpenseReportController extends Controller
{
    public function officeExpense(OfficeExpenseFilter $filter)
    {
        $dateFrom = $filter->input('date_from');
        $dateTo = $filter->input('date_to');
        $forceAsync = $filter->input('force_async', false);
        $generateReport = new GenerateReports(ReportType::OFFICE_CODE->value, $dateFrom, $dateTo);
        $generateReport->handle();
        $cacheKey = GenerateReports::getCacheKey();
        if (Cache::has($cacheKey)) {
            return response()->json(array_merge(
                Cache::get($cacheKey),
                [
                    'from_cache' => true,
                ]
            ));
        }
        $daysDiff = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo));
        $threshold = config('reports.large_report_threshold', 90);
        $isLargeReport = $daysDiff > $threshold || $forceAsync;
        if ($isLargeReport) {
            $jobStatusKey = "job_processong_{$cacheKey}";
            if (Cache::has($jobStatusKey)) {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Report generation is in progress',
                    'status' => 'processing',
                    'cache_key' => $cacheKey,
                    'estimated_completion' => 'Please check back in a few moments',
                    'estimated_wait_seconds' => 30,
                ], 202);
            }
            Cache::put($jobStatusKey, true, now()->addMinutes(10));
            GenerateReports::dispatch($dateFrom, $dateTo);
            return new JsonResponse([
                'success' => true,
                'message' => 'Large report detected. Generation started in background',
                'status' => 'queued',
                'cache_key' => $cacheKey,
                'date_range_days' => $daysDiff,
                'polling_endpoint' => route('reports.office-expense.status', ['cache_key' => $cacheKey]),
                'estimated_wait_seconds' => 30,
            ], 202);
        }
        try {
            $data = OfficeExpenseService::
        } catch (\Exception $e) {

        }
    }
}
