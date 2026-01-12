<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ReportType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\OfficeExpenseFilter;
use App\Jobs\GenerateReports;
use App\Services\Reports\OfficeExpenseService;
use Illuminate\Http\Request;
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
        $cacheKey = GenerateReports::getCacheKey(
            ReportType::OFFICE_CODE->value,
            $dateFrom,
            $dateTo
        );
        if (Cache::has($cacheKey)) {
            return new JsonResponse(array_merge(
                Cache::get($cacheKey),
                ['from_cache' => true]
            ));
        }
        $daysDiff = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo));
        $threshold = config('reports.large_report_threshold', 90);
        $isLargeReport = $daysDiff > $threshold || $forceAsync;
        if ($isLargeReport) {
            $jobStatusKey = "job_processing_{$cacheKey}";

            if (Cache::has($jobStatusKey)) {
                return new JsonResponse([
                    'success' => true,
                    'status' => 'processing',
                    'message' => 'Report generation is in progress',
                    'cache_key' => $cacheKey,
                ], 202);
            }
            Cache::put($jobStatusKey, true, now()->addMinutes(10));
            GenerateReports::dispatch(
                ReportType::OFFICE_CODE->value,
                $dateFrom,
                $dateTo
            );
            return new JsonResponse([
                'success' => true,
                'status' => 'queued',
                'message' => 'Large report queued for background generation',
                'cache_key' => $cacheKey,
                'polling_endpoint' => route(
                    'reports.office-expense.status',
                    ['cache_key' => $cacheKey]
                ),
            ], 202);
        }
        try {
            $reportData = OfficeExpenseService::officeExpenseReport(
                $dateFrom,
                $dateTo
            );
            $payload = [
                'success' => true,
                'message' => 'Office Expense Report Successfully Retrieved.',
                'data' => $reportData,
                'generated_at' => now()->toISOString(),
                'generation_time_seconds' => 0,
            ];
            Cache::put(
                $cacheKey,
                $payload,
                now()->addMinutes(config('reports.cache_duration', 1440))
            );
            return new JsonResponse(array_merge($payload, [
                'from_cache' => false,
            ]));
        } catch (\Throwable $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to generate report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function checkStatus(Request $request)
    {
        $cacheKey = $request->input('cache_key');
        if (!$cacheKey) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Cache key is required'
            ], 400);
        }
        if (Cache::has($cacheKey)) {
            return new JsonResponse(array_merge(
                Cache::get($cacheKey),
                [
                    'status' => 'completed',
                    'message' => 'Report is ready',
                ]
            ));
        }
        $jobStatusKey = "job_processing_{$cacheKey}";
        if (Cache::has($jobStatusKey)) {
            return new JsonResponse([
                'success' => true,
                'status' => 'processing',
                'message' => 'Report generation is still in progress',
            ], 202);
        }
        return new JsonResponse([
            'success' => false,
            'status' => 'not_found',
            'message' => 'Report not found. It may have expired or failed to generate.',
        ], 404);
    }

    public function generateAsync(OfficeExpenseFilter $filter)
    {
        $dateFrom = $filter->input('date_from');
        $dateTo = $filter->input('date_to');
        new GenerateReports(ReportType::OFFICE_CODE->value, $dateFrom, $dateTo);
        $cacheKey = GenerateReports::getCacheKey();
        if (Cache::has($cacheKey)) {
            return new JsonResponse(array_merge(
                Cache::get($cacheKey),
                [
                    'message' => 'Report already exists',
                    'status' => 'completed',
                ]
            ));
        }
        GenerateReports::dispatch(ReportType::OFFICE_CODE->value, $dateFrom, $dateTo);
        Cache::put("job_processing_{$cacheKey}", true, now()->addMinutes(10));
        return new JsonResponse([
            'success' => true,
            'message' => 'Report generation started',
            'status' => 'queued',
            'cache_key' => $cacheKey,
            'polling_endpoint' => route('reports.office-expense.status', ['cache_key' => $cacheKey]),
        ], 202);
    }
}
