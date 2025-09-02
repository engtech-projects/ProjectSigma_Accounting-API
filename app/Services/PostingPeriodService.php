<?php

namespace App\Services;

use App\Enums\PostingPeriodStatusType;
use App\Models\FiscalYear;
use App\Models\PostingPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostingPeriodService
{
    protected $casts = [
        'status' => PostingPeriodStatusType::class,
    ];

    public static function getPaginated(array $filters = [])
    {
        $query = PostingPeriod::query();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['start_date'])) {
            $query->where('start_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('end_date', '<=', $filters['end_date']);
        }

        return $query->withDetails()->orderByDesc('created_at')->paginate(config('services.pagination.limit'));
    }

    public static function create(array $data): PostingPeriod
    {
        return PostingPeriod::create($data);
    }

    public static function update(PostingPeriod $postingPeriod, array $data): bool
    {
        return $postingPeriod->update($data);
    }

    public static function delete(PostingPeriod $postingPeriod): bool
    {
        return $postingPeriod->delete();
    }

    public static function findById(int $id): ?PostingPeriod
    {
        return PostingPeriod::find($id);
    }

    public function createPostingPeriod(): array
    {
        return DB::transaction(function () {
            $currentDate = Carbon::now();
            $nextMonth = $currentDate->copy()->addMonth();
            $this->ensureCurrentMonthAndClosePrevious($currentDate);
            $fiscalYear = $this->ensureFiscalYear($nextMonth);
            $postingPeriod = $this->createNextMonthPostingPeriod($fiscalYear, $nextMonth);

            return compact('fiscalYear', 'postingPeriod');
        });
    }

    private function ensureCurrentMonthAndClosePrevious(Carbon $currentDate): void
    {
        $currentMonthStart = $currentDate->copy()->startOfMonth();
        $fiscalYear = $this->ensureFiscalYear($currentDate);
        $currentMonthPeriod = $fiscalYear->postingPeriods()
            ->whereDate('start_date', $currentMonthStart)
            ->first();
        if (! $currentMonthPeriod) {
            $currentMonthPeriod = $fiscalYear->postingPeriods()->firstOrCreate([
                'start_date' => $currentMonthStart],
                [
                    'end_date' => $currentMonthStart->copy()->endOfMonth(),
                    'status' => PostingPeriodStatusType::OPEN,
                ]
            );
            Log::channel('posting-period')->info('Created current month posting period', [
                'fiscal_year_id' => $fiscalYear->id,
                'start_date' => $currentMonthStart->toDateString(),
                'posting_period_id' => $currentMonthPeriod->id,
            ]);
        }
        $openStatus = PostingPeriodStatusType::OPEN->value;
        $closedStatus = PostingPeriodStatusType::CLOSED->value;
        $affectedRows = $fiscalYear->postingPeriods()
            ->where('start_date', '<', $currentMonthStart)
            ->where('status', $openStatus)
            ->update(['status' => $closedStatus]);
        if ($affectedRows > 0) {
            Log::channel('posting-period')->info('Closed previous posting periods', [
                'fiscal_year_id' => $fiscalYear->id,
                'closed_count' => $affectedRows,
                'before_date' => $currentMonthStart->toDateString(),
            ]);
        }
    }

    private function ensureFiscalYear(Carbon $targetDate): FiscalYear
    {
        $fiscalYear = FiscalYear::where('period_start', '<=', $targetDate)
            ->where('period_end', '>=', $targetDate)
            ->first();
        if ($fiscalYear) {
            return $fiscalYear;
        }
        $lastOpenFiscalYear = FiscalYear::where('status', PostingPeriodStatusType::OPEN->value)
            ->latest('period_end')
            ->first();
        if ($lastOpenFiscalYear) {
            throw new \DomainException('Cannot create new fiscal year - a previous fiscal year is still open.');
        }

        return FiscalYear::create([
            'period_start' => $targetDate->copy()->startOfYear(),
            'period_end' => $targetDate->copy()->endOfYear(),
            'status' => PostingPeriodStatusType::OPEN,
        ]);
    }

    private function createNextMonthPostingPeriod(FiscalYear $fiscalYear, Carbon $nextMonth): PostingPeriod
    {
        $startOfNextMonth = $nextMonth->copy()->startOfMonth();
        $endOfNextMonth = $nextMonth->copy()->endOfMonth();
        $existingPeriod = $fiscalYear->postingPeriods()
            ->where('start_date', $startOfNextMonth)
            ->first();
        if ($existingPeriod) {
            return $existingPeriod;
        }

        return $fiscalYear->postingPeriods()->create([
            'start_date' => $startOfNextMonth,
            'end_date' => $endOfNextMonth,
            'status' => PostingPeriodStatusType::OPEN,
        ]);
    }
}
