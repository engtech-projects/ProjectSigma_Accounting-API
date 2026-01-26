<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\StatementOfCashFlowFilterRequest;
use App\Enums\ReportType;
use App\Jobs\GenerateReports;
use App\Services\Reports\StatementOfCashFlowService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;

class StatementOfCashFlowReportController extends Controller
{
    public function statementOfCashFlow(StatementOfCashFlowFilterRequest $request)
    {
        $params = [
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'force_async' => $request->input('force_async', false),
        ];
        $generateReport = new GenerateReports(ReportType::STATEMENT_CASH_FLOW->value, $params);
        $cacheKey = $generateReport->getCacheKey();
        if (Cache::has($cacheKey)) {
            return new JsonResponse(array_merge(
                Cache::get($cacheKey),
                ['from_cache' => true]
            ));
        }
        $daysDiff = Carbon::parse($params['date_from'])->diffInDays(Carbon::parse($params['date_to']));
        $threshold = config('reports.large_report_threshold', 90);
        $isLargeReport = $daysDiff > $threshold || $params['force_async'];
        if ($isLargeReport) {
            $jobStatusKey = "job_processing_{$cacheKey}";
            if (Cache::has($jobStatusKey)) {
                return new JsonResponse([
                    'success' => true,
                    'status' => 'processing',
                    'message' => 'Report generation is in progress.',
                    'cache_key' => $cacheKey,
                ], 202);
            }
            Cache::put($jobStatusKey, true, now()->addMinutes(10));
            $generateReport->dispatch();
            return new JsonResponse([
                'success' => true,
                'status' => 'queued',
                'message' => 'Large report queued for generation',
                'cache_key' => $cacheKey,
                'polling_endpoint' => route(
                    'reports.statement-of-cash-flow.status',
                    ['cache_key' => $cacheKey]
                )
            ], 202);
        }
        try {
            $reportData = StatementOfCashFlowService::statementOfCashFlowReport(
                $params['date_from'],
                $params['date_to']
            );
            $payload = [
                'success' => true,
                'message' => 'Statement of Cash Flow Report Successfully Retrieved.',
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
                'status' => 'completed',
                'message' => 'Report is ready',
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

    public function generateAsync(StatementOfCashFlowFilterRequest $filter)
    {
        $params = [
            'date_from' => $filter->input('date_from'),
            'date_to' => $filter->input('date_to'),
            'force_async' => $filter->input('force_async', false),
        ];
        $generateReport = new GenerateReports(ReportType::STATEMENT_CASH_FLOW->value, $params);
        $cacheKey = $generateReport->getCacheKey();
        if (Cache::has($cacheKey)) {
            return new JsonResponse([
                ...Cache::get($cacheKey),
                'message' => 'Report already exists',
                'status' => 'completed',
            ]);
        }
        dispatch($generateReport);
        return new JsonResponse([
            'success' => true,
            'status' => 'queued',
            'message' => 'Report generation queued',
            'cache_key' => $cacheKey,
        ], 202);
    }
}
