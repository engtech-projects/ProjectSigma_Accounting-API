<?php

namespace App\Services;

use App\Enums\PostingPeriodStatusType;
use App\Models\FiscalYear;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FiscalYearService
{
    /**
     * Create a new fiscal year
     */
    public static function create(array $data): FiscalYear
    {
        return FiscalYear::create($data);
    }

    /**
     * Create fiscal year for the next year if needed
     */
    public static function createNextYearFiscalYear(): FiscalYear
    {
        return DB::transaction(function () {
            $currentDate = Carbon::now();
            $nextYear = $currentDate->copy()->addYear();

            $existingFiscalYear = FiscalYear::where('period_start', '<=', $nextYear->startOfYear())
                ->where('period_end', '>=', $nextYear->endOfYear())
                ->first();
            if ($existingFiscalYear) {
                return $existingFiscalYear;
            }
            $lastOpenFiscalYear = FiscalYear::where('status', PostingPeriodStatusType::OPEN->value)
                ->where('period_end', '>=', $currentDate)
                ->latest('period_end')
                ->first();
            if ($lastOpenFiscalYear && $lastOpenFiscalYear->period_end >= $nextYear->startOfYear()) {
                $lastOpenFiscalYear->update(['status' => PostingPeriodStatusType::CLOSED]);
                Log::channel('fiscal-year')->info('Auto-closed previous fiscal year', [
                    'closed_fiscal_year_id' => $lastOpenFiscalYear->id,
                    'period_start' => $lastOpenFiscalYear->period_start->toDateString(),
                    'period_end' => $lastOpenFiscalYear->period_end->toDateString(),
                    'reason' => 'Creating new fiscal year',
                    'executed_by' => 'console',
                    'timestamp' => now(),
                ]);
            }
            $fiscalYear = FiscalYear::create([
                'period_start' => $nextYear->startOfYear(),
                'period_end' => $nextYear->endOfYear(),
                'status' => PostingPeriodStatusType::OPEN,
            ]);
            Log::channel('fiscal-year')->info('Fiscal Year Created', [
                'fiscal_year_id' => $fiscalYear->id,
                'period_start' => $fiscalYear->period_start->toDateString(),
                'period_end' => $fiscalYear->period_end->toDateString(),
                'executed_by' => 'console',
                'timestamp' => now(),
            ]);
            return $fiscalYear;
        });
    }

    /**
     * Get paginated fiscal years
     */
    public static function getPaginated(array $filters = [])
    {
        $query = FiscalYear::query();
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('period_start', 'like', "%{$search}%")
                    ->orWhere('period_end', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['period_start'])) {
            $query->where('period_start', '>=', $filters['period_start']);
        }
        if (isset($filters['period_end'])) {
            $query->where('period_end', '<=', $filters['period_end']);
        }
        return $query->withDetails()->orderByDesc('created_at')->paginate(config('services.pagination.limit'));
    }

    /**
     * Find fiscal year by ID
     */
    public static function findById(int $id): ?FiscalYear
    {
        return FiscalYear::find($id);
    }

    /**
     * Update fiscal year
     */
    public static function update(FiscalYear $fiscalYear, array $data): bool
    {
        return $fiscalYear->update($data);
    }

    /**
     * Delete fiscal year
     */
    public static function delete(FiscalYear $fiscalYear): bool
    {
        return $fiscalYear->delete();
    }
}
