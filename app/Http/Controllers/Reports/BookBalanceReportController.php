<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\BookBalanceFilterRequest;
use App\Jobs\GenerateReports;
use App\Enums\ReportType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use App\Services\Reports\BookBalanceService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;

class BookBalanceReportController extends Controller
{
    public function bookBalance(BookBalanceFilterRequest $filter)
    {
        $params = [
            'date_from' => $filter->input('date_from'),
            'date_to' => $filter->input('date_to'),
            'force_async' => $filter->input('force_async', false),
            'year' => $filter->input('year'),
        ];
        $generateReport = new GenerateReports(ReportType::BOOK_BALANCE->value, $params);
        $generateReport->handle();
        $cacheKey = GenerateReports::getCacheKey();
        if (Cache::has($cacheKey)) {
            return response()->json([
                ...Cache::get($cacheKey),
                'from_cache' => true,
            ]);
        }
        $daysDiff = Carbon::parse($params['date_from'])->diffInDays(Carbon::parse($params['date_to']));
        $threshold = config('reports.large_report_threshold', 90);
        $isLargeReport = $daysDiff > $threshold || $params['force_async'];
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
            Cache::put($jobStatusKey, true, now()->addMinutes(30));
            GenerateReports::dispatch(ReportType::BOOK_BALANCE->value, $params['date_from'], $params['date_to']);
            return new JsonResponse([
                'success' => true,
                'message' => 'Large report detected. Generation started in background.',
                'status' => 'queued',
                'cache_key' => $cacheKey,
                'date_range_days' => $daysDiff,
                'polling_endpoint' => route('reports.book-balance.status', ['cache_key' => $cacheKey]),
                'estimated_wait_seconds' => 30,
            ], 202);
        }
        try {
            $startTime = microtime(true);
            $data = BookBalanceService::bookBalanceReport($params['date_from'], $params['date_to']);
            $generationTime = round(microtime(true) - $startTime, 2);
            $data['generated_at'] = now()->toISOString();
            $data['generation_time_seconds'] = $generationTime;
            Cache::put($cacheKey, $data, now()->addMinutes(config('reports.cache_duration', 1440)));
            return new JsonResponse([
                ...$data,
                'from_cache' => false,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to generate report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkBalance(Request $request)
    {
        $cacheKey = $request->input('cache_key');
        if (!$cacheKey) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Cache key is required'
            ], 400);
        }
        if (Cache::has($cacheKey)) {
            return new JsonResponse([
                'status' => 'completed',
                'message' => 'Report is ready',
                'data' => Cache::get($cacheKey),
            ]);
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
}
