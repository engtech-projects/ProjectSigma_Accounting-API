<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ReportType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\IncomeStatementFilterRequest;
use App\Jobs\GenerateReports;
use App\Services\Reports\IncomeStatementService;
use Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncomeStatementReportController extends Controller
{
    public function incomeStatement(IncomeStatementFilterRequest $filter)
    {
        $dateFrom = $filter->input('date_from');
        $dateTo = $filter->input('date_to');
        $forceAsync = $filter->input('force_async', false);
        $generateReport = new GenerateReports(ReportType::INCOME_STATEMENT->value, $dateFrom, $dateTo);
        $cacheKey = $generateReport->getCacheKey();
        if (Cache::has($cacheKey)) {
            return response()->json(array_merge(
                Cache::get($cacheKey),
                [
                    'from_cache' => true,
                ]
            ));
        }
        $daysDiff = $generateReport->getDateDiff();
        $threshold = config('reports.large_report_threshold', 90);
        $isLargeReport = $daysDiff > $threshold || $forceAsync;
        if ($isLargeReport) {
            $jobStatusKey = "job_processing_{$cacheKey}";
            if (Cache::has($jobStatusKey)) {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Report generation is in progress',
                    'status' => 'processing',
                    'cache_key' => $cacheKey,
                    'estimated_completion' => 'Please check back in a few moments',
                ], 202);
            }
            Cache::put($jobStatusKey, true, now()->addMinutes(10));
            dispatch($generateReport);
            return new JsonResponse([
                'success' => true,
                'message' => 'Large report detected. Generation started in background.',
                'status' => 'queued',
                'cache_key' => $cacheKey,
                'date_range_days' => $daysDiff,
                'polling_endpoint' => route('reports.income-statement.status', ['cache_key' => $cacheKey]),
                'estimated_wait_seconds' => 30,
            ], 202);
        }
        try {
            $data = IncomeStatementService::incomeStatementReport($generateReport->getDateFrom(), $generateReport->getDateTo());
            $data['generated_at'] = now()->toISOString();
            $data['generation_time_seconds'] = 0;
            Cache::put($cacheKey, $data, now()->addMinutes(config('reports.cache_duration', 1440)));
            return new JsonResponse(array_merge($data, [
                'from_cache' => false,
            ]));
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to generate report',
                'error' => $e->getMessage()
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
    public function generateAsync(IncomeStatementFilterRequest $filter)
    {
        $dateFrom = $filter->input('date_from');
        $dateTo = $filter->input('date_to');
        $generateReport = new GenerateReports(ReportType::INCOME_STATEMENT->value, $dateFrom, $dateTo);
        $cacheKey = $generateReport->getCacheKey();
        if (Cache::has($cacheKey)) {
            return new JsonResponse(array_merge(
                Cache::get($cacheKey),
                [
                    'message' => 'Report already exists',
                    'status' => 'completed',
                ]
            ));
        }
        dispatch($generateReport);
        Cache::put("job_processing_{$cacheKey}", true, now()->addMinutes(10));
        return new JsonResponse([
            'success' => true,
            'message' => 'Report generation started',
            'status' => 'queued',
            'cache_key' => $cacheKey,
            'polling_endpoint' => route('reports.income-statement.status', ['cache_key' => $cacheKey]),
        ], 202);
    }
}
