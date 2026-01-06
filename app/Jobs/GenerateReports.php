<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use App\Services\Report\BalanceSheetService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenerateReports implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $dateFrom;
    protected $dateTo;

    public $timeout = 300;
    public $tries = 3;

    public function __construct(string $dateFrom, string $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function handle(): void
    {
        $startTime = now();
        $cacheKey = self::getCacheKey($this->dateFrom, $this->dateTo);
        $jobStatusKey = "job_processing_{$cacheKey}";

        try {
            $startDate = Carbon::parse($this->dateFrom)->startOfDay();
            $endDate = Carbon::parse($this->dateTo)->endOfDay();

            $filter = [
                "date_from" => $startDate->format('Y-m-d'),
                "date_to" => $endDate->format('Y-m-d'),
            ];

            $dataToCache = BalanceSheetService::balanceSheetReport($filter);

            // Add metadata
            $dataToCache['generated_at'] = now()->toISOString();
            $dataToCache['generation_time_seconds'] = now()->diffInSeconds($startTime);

            Cache::put($cacheKey, $dataToCache, now()->addMinutes(1440));
            Cache::forget($jobStatusKey);

            Log::info("Balance sheet generated successfully", [
                'cache_key' => $cacheKey,
                'duration_seconds' => now()->diffInSeconds($startTime),
            ]);

        } catch (\Exception $e) {
            Cache::forget($jobStatusKey);
            Log::error("Failed to generate balance sheet", [
                'error' => $e->getMessage(),
                'date_from' => $this->dateFrom,
                'date_to' => $this->dateTo,
            ]);
            throw $e;
        }
    }

    public static function getCacheKey(string $dateFrom, string $dateTo): string
    {
        $startDate = Carbon::parse($dateFrom);
        $endDate = Carbon::parse($dateTo);

        return 'balance_sheet_' . $startDate->format('Y_m_d') . '_to_' . $endDate->format('Y_m_d');
    }
}
