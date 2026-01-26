<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ReportType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\OfficeCodeFilterRequest;
use App\Jobs\GenerateReports;
use App\Services\Reports\OfficeCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\JsonResponse;

class OfficeCodeReportController extends Controller
{
    public function officeCode(OfficeCodeFilterRequest $filter)
    {
        $params = [
            'date_from' => $filter->input('date_from'),
            'date_to' => $filter->input('date_to'),
            'force_async' => $filter->input('force_async', false),
            'year' => $filter->input('year'),
        ];
        $generateReport = new GenerateReports(ReportType::OFFICE_CODE->value, $params);
        $cacheKey = $generateReport->getCacheKey();
        if (Cache::has($cacheKey)) {
            return response()->json([...Cache::get($cacheKey),
                'from_cache' => true,
            ]);
        }
        $daysDiff = $generateReport->getDateDiff();
        $threshold = config('reports.large_report_threshold', 90);
        $isLargeReport = $daysDiff > $threshold || $params['force_async'];
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
            $generateReport->dispatch();
            return new JsonResponse([
                'success' => true,
                'status' => 'queued',
                'message' => 'Large report queued for background generation',
                'cache_key' => $cacheKey,
                'polling_endpoint' => route(
                    'reports.office-code.status',
                    ['cache_key' => $cacheKey]
                ),
            ], 202);
        }
        try {
            $reportData = OfficeCodeService::officeCodeReport(
                $params['date_from'],
                $params['date_to']
            );
            $payload = [
                'success' => true,
                'message' => 'Office Code Report Successfully Retrieved.',
                'data' => $reportData,
                'generated_at' => now()->toISOString(),
                'generation_time_seconds' => 0,
            ];
            Cache::put(
                $cacheKey,
                $payload,
                now()->addMinutes(config('reports.cache_duration', 1440))
            );
            return new JsonResponse([
                ...$payload,
                'from_cache' => false
            ]);
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
            return new JsonResponse([
                ...Cache::get($cacheKey),
                [
                    'status' => 'completed',
                    'message' => 'Report is ready',
                ]
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

    public function generateAsync(OfficeCodeFilterRequest $filter)
    {
        $params = [
            'date_from' => $filter->input('date_from'),
            'date_to' => $filter->input('date_to'),
            'force_async' => $filter->input('force_async', false),
            'year' => $filter->input('year'),
        ];
        $generateReport = new GenerateReports(ReportType::OFFICE_CODE->value, $params);
        $cacheKey = $generateReport->getCacheKey();
        if (Cache::has($cacheKey)) {
            return new JsonResponse([
                ...Cache::get($cacheKey),
                [
                    'message' => 'Report already exists',
                    'status' => 'completed',
                ]
            ]);
        }
        dispatch($generateReport);
        Cache::put("job_processing_{$cacheKey}", true, now()->addMinutes(10));
        return new JsonResponse([
            'success' => true,
            'message' => 'Report generation started',
            'status' => 'queued',
            'cache_key' => $cacheKey,
            'polling_endpoint' => route('reports.office-code.status', ['cache_key' => $cacheKey]),
        ], 202);
    }
}
