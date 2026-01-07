<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ReportType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\BalanceSheetRequestFilter;
use App\Jobs\GenerateReports;
use App\Services\Reports\BalanceSheetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class BalanceSheetReportController extends Controller
{
    public function balanceSheet(BalanceSheetRequestFilter $filter)
    {
        $dateFrom = $filter->input('date_from');
        $dateTo = $filter->input('date_to');
        $forceAsync = $filter->input('force_async', false);
        $generateReport = new GenerateReports(ReportType::BALANCE_SHEET->value, $dateFrom, $dateTo);
        $generateReport->handle();
        $cacheKey = GenerateReports::getCacheKey();

        // Check cache first
        if (Cache::has($cacheKey)) {
            return response()->json(array_merge(
                Cache::get($cacheKey),
                [
                    'from_cache' => true,
                ]
            ));
        }

        // Calculate date range
        $daysDiff = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo));
        $threshold = config('reports.large_report_threshold', 90);
        $isLargeReport = $daysDiff > $threshold || $forceAsync;

        // For large reports, use async job
        if ($isLargeReport) {
            $jobStatusKey = "job_processing_{$cacheKey}";

            if (Cache::has($jobStatusKey)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Report generation is in progress',
                    'status' => 'processing',
                    'cache_key' => $cacheKey,
                    'estimated_completion' => 'Please check back in a few moments',
                ], 202);
            }

            Cache::put($jobStatusKey, true, now()->addMinutes(10));
            GenerateReports::dispatch($dateFrom, $dateTo);

            return response()->json([
                'success' => true,
                'message' => 'Large report detected. Generation started in background.',
                'status' => 'queued',
                'cache_key' => $cacheKey,
                'date_range_days' => $daysDiff,
                'polling_endpoint' => route('reports.balance-sheet.status', ['cache_key' => $cacheKey]),
                'estimated_wait_seconds' => 30,
            ], 202);
        }

        // Generate immediately for small reports
        try {
            $data = BalanceSheetService::balanceSheetReport($dateFrom, $dateTo);

            // Add metadata
            $data['generated_at'] = now()->toISOString();
            $data['generation_time_seconds'] = 0;

            // Cache the data
            Cache::put($cacheKey, $data, now()->addMinutes(config('reports.cache_duration', 1440)));

            return response()->json(array_merge($data, [
                'from_cache' => false,
            ]));
        } catch (\Exception $e) {
            return response()->json([
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
            return response()->json([
                'success' => false,
                'message' => 'Cache key is required'
            ], 400);
        }

        // Check if report is ready
        if (Cache::has($cacheKey)) {
            return response()->json(array_merge(
                Cache::get($cacheKey),
                [
                    'status' => 'completed',
                    'message' => 'Report is ready',
                ]
            ));
        }

        // Check if still processing
        $jobStatusKey = "job_processing_{$cacheKey}";
        if (Cache::has($jobStatusKey)) {
            return response()->json([
                'success' => true,
                'status' => 'processing',
                'message' => 'Report generation is still in progress',
            ], 202);
        }

        // Not found
        return response()->json([
            'success' => false,
            'status' => 'not_found',
            'message' => 'Report not found. It may have expired or failed to generate.',
        ], 404);
    }

    public function generateAsync(BalanceSheetRequestFilter $filter)
    {
        $cacheKey = GenerateReports::getCacheKey();

        // Check if already cached
        if (Cache::has($cacheKey)) {
            return response()->json(array_merge(
                Cache::get($cacheKey),
                [
                    'message' => 'Report already exists',
                    'status' => 'completed',
                ]
            ));
        }

        // Dispatch job
        GenerateReports::dispatch();
        Cache::put("job_processing_{$cacheKey}", true, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'message' => 'Report generation started',
            'status' => 'queued',
            'cache_key' => $cacheKey,
            'polling_endpoint' => route('reports.balance-sheet.status', ['cache_key' => $cacheKey]),
        ], 202);
    }
}
