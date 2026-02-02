<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ReportType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\MonthlyProjectExpenseFilterRequest;
use App\Jobs\GenerateReports;
use App\Services\Reports\MonthlyProjectExpensesService;
use Cache;
use Illuminate\Http\JsonResponse;
use Request;

class MonthlyProjectExpenseReportController extends Controller
{
    public function monthlyProjectExpense(MonthlyProjectExpenseFilterRequest $filter)
    {
        $params = [
            'date_from' => $filter->input('date_from'),
            'date_to' => $filter->input('date_to'),
            'force_async' => $filter->input('force_async', false),
            'year' => $filter->input('year'),
        ];
        $generateReport = new GenerateReports(ReportType::MONTHLY_PROJECT_EXPENSES->value, $params);
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
                'polling_endpoint' => route('monthly-project-expense.status', ['cache_key' => $cacheKey]),
                'estimated_wait_seconds' => 30,
            ], 202);
        }
        try {
            $data = MonthlyProjectExpensesService::monthlyProjectExpenseReport($params['year']);
            $data['generated_at'] = now()->toISOString();
            $data['generation_time_seconds'] = 0;
            Cache::put($cacheKey, $data, now()->addMinutes(config('reports.cache_duration', 1440)));
            return new JsonResponse([...$data,
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
            return new JsonResponse(
                [
                ...Cache::get($cacheKey),
                'status' => 'completed',
                'message' => 'Report is ready',
            ]
            );
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
    public function generateAsync(MonthlyProjectExpenseFilterRequest $filter)
    {
        $params = [
            'date_from' => $filter->input('date_from'),
            'date_to' => $filter->input('date_to'),
            'force_async' => $filter->input('force_async', false),
            'year' => $filter->input('year'),
        ];
        $generateReport = new GenerateReports(ReportType::MONTHLY_PROJECT_EXPENSES->value, $params);
        $cacheKey = $generateReport->getCacheKey();
        if (Cache::has($cacheKey)) {
            return new JsonResponse([...Cache::get($cacheKey),
                'message' => 'Report already exists',
                'status' => 'completed',
            ]);
        }
        dispatch($generateReport);
        Cache::put("job_processing_{$cacheKey}", true, now()->addMinutes(10));
        return new JsonResponse([
            'success' => true,
            'message' => 'Report generation started',
            'status' => 'queued',
            'cache_key' => $cacheKey,
            'polling_endpoint' => route('reports.monthly-project-expense.status', ['cache_key' => $cacheKey]),
        ], 202);
    }
}
