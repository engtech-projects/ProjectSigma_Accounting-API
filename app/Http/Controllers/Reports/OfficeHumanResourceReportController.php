<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Enums\ReportType;
use App\Http\Requests\Reports\OfficeHumanResourceFilterRequest;
use App\Jobs\GenerateReports;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use App\Services\Reports\OfficeHumanResourceService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OfficeHumanResourceReportController extends Controller
{
    public function officeHumanResource(OfficeHumanResourceFilterRequest $filter)
    {
        $dateFrom = $filter->input('date_from');
        $dateTo = $filter->input('date_to');
        $forceAsync = $filter->input('force_async', false);
        $cacheKey = GenerateReports::getCacheKey(
            ReportType::BUDGET_REPORT->value,
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
                    'message' => 'Report generation is in progress',
                    'status' => 'processing',
                    'cache_key' => $cacheKey,
                    'estimated_completion' => 'Please check back in a few minutes',
                ], 202);
            }
            Cache::put($jobStatusKey, true, now()->addMinutes(10));
            GenerateReports::dispatch(
                ReportType::BUDGET_REPORT->value,
                $dateFrom,
                $dateTo
            );
            return new JsonResponse([
                'success' => true,
                'status' => 'queued',
                'message' => 'Large report queued for background generation',
                'cache_key' => $cacheKey,
                'polling_endpoint' => route(
                    'reports.office-human-resource.status',
                    ['cache_key' => $cacheKey]
                ),
            ], 202);
        }
        try {
            $data = OfficeHumanResourceService::officeHumanResource($dateFrom, $dateTo);
            $payload = [
                'success' => true,
                'message' => 'Office Human Resource Report Successfully Retrieved.',
                'data' => $data,
                'generated_at' => now()->toISOString(),
                'generation_time_seconds' => 0,
            ];
            Cache::put($cacheKey, $payload, now()->addMinutes(config('reports.cache_duration', 1440)));
            return new JsonResponse(array_merge($payload, [
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
                    'success' => true,
                    'message' => 'Report is ready',
            ]
            ));
        }
        $jobStatusKey = "job_processing_{$cacheKey}";
        if (Cache::has($jobStatusKey)) {
            return new JsonResponse([
                'success' => true,
                'status' => 'processing',
                'message' => 'Report is still being generated',
            ], 202);
        }
        return new JsonResponse([
            'success' => false,
            'status' => 'not_found',
            'message' => 'Report not found. It may have expired or failed to generate.',
            'cache_key' => $cacheKey,
        ], 404);
    }
    public function generateAsync(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $generateReport = new GenerateReports(ReportType::OFFICE_HUMAN_RESOURCE->value, $dateFrom, $dateTo);
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
        GenerateReports::dispatch(ReportType::OFFICE_HUMAN_RESOURCE->value, $dateFrom, $dateTo);
        Cache::put("job_processing_{$cacheKey}", true, now()->addMinutes(10));
        return new JsonResponse([
                'success' => true,
                'message' => 'Report generation started',
                'status' => 'queued',
                'cache_key' => $cacheKey,
                'polling_endpoint' => route('reports.office-human-resource.status', ['cache_key' => $cacheKey]),
            ], 202);
    }
}
