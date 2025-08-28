<?php
namespace App\Http\Controllers;

use App\Enums\PostingPeriodStatusType;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Models\PostingPeriod;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CreatePostingPeriod extends Controller
{
    /**
     * Handle the incoming request.
     * Create posting period for the NEXT month
     */
    public function __invoke(): JsonResponse
    {
        $currentDate = Carbon::now();
        $nextMonth = $currentDate->copy()->addMonth();
        
        try {
            DB::beginTransaction();

            $this->ensureCurrentMonthAndClosePrevious($currentDate);
            $postingPeriod = $this->ensureYearlyPostingPeriod($nextMonth);
            $period = $this->createNextMonthPostingPeriod($postingPeriod, $nextMonth);

            DB::commit();
            
            Log::info('Posting Period Created: ', [
                'posting_period_id' => $postingPeriod->id,
                'period_id' => $period->id,
                'next_month' => $nextMonth->format('Y-m'),
                'current_date' => $currentDate->toDateString(),
            ]); 
            
            return new JsonResponse([
                'success' => true,
                'message' => 'Posting Period Successfully Created',
                'data' => [
                    'posting_period' => $postingPeriod->fresh(['periods']),
                    'period' => $period,
                    'create_for_month' => $nextMonth->format('F Y'),
                    'execution_date' => $currentDate->toDateString(),
                ],
            ], 201); 
            
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('Database error while creating posting period', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
            ]);
            return new JsonResponse([
                'success' => false,
                'message' => 'Database error occurred',
                'data' => null,
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Posting Period Failed to Create: ', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'current_date' => $currentDate->toDateString(),
                'target_month' => $nextMonth->format('Y-m'),
            ]);
            
            return new JsonResponse([
                'success' => false,
                'message' => 'Posting Period Failed to Create: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Ensure current month exists and close all previous months
     */
    private function ensureCurrentMonthAndClosePrevious(Carbon $currentDate): void
    {
        $currentMonthStart = $currentDate->copy()->startOfMonth();
        
        $currentYearPostingPeriod = $this->ensureYearlyPostingPeriod($currentDate);
        
        $currentMonthPeriod = $currentYearPostingPeriod->periods()
            ->where('start_date', $currentMonthStart)
            ->first();
        
        if (!$currentMonthPeriod) {
            $currentMonthPeriod = $currentYearPostingPeriod->periods()->create([
                'start_date' => $currentMonthStart,
                'end_date' => $currentDate->copy()->endOfMonth(),
                'status' => PostingPeriodStatusType::OPEN,
            ]);
            
            Log::info('Created current month period', [
                'current_month' => $currentDate->format('Y-m'),
                'period_id' => $currentMonthPeriod->id,
            ]);
        }
        
        $closedPeriodsCount = $currentYearPostingPeriod->periods()
            ->where('start_date', '<', $currentMonthStart)
            ->where('status', PostingPeriodStatusType::OPEN)
            ->update(['status' => PostingPeriodStatusType::CLOSED]);
        
        if ($closedPeriodsCount > 0) {
            Log::info('Closed previous month periods', [
                'closed_count' => $closedPeriodsCount,
                'current_month' => $currentDate->format('Y-m'),
            ]);
        }
    }

    /**
     * Ensure posting period exists for the given month
     */
    private function ensureYearlyPostingPeriod(Carbon $targetDate): PostingPeriod
    {
        $postingPeriod = PostingPeriod::where('period_start', '<=', $targetDate)
            ->where('period_end', '>=', $targetDate)
            ->first();
            
        if (!$postingPeriod) {
            $hasOpenPeriods = PostingPeriod::where('status', PostingPeriodStatusType::OPEN->value)->exists();
            if (!$lastOpenPeriod || !$lastOpenPeriod->checkIfStatusIsOpenYearly()) {
                $postingPeriod = PostingPeriod::create([
                    'period_start' => $targetDate->copy()->startOfYear(),
                    'period_end' => $targetDate->copy()->endOfYear(),
                    'status' => PostingPeriodStatusType::OPEN,
                ]);
                
                Log::info('Created new yearly posting period', [
                    'target_year' => $targetDate->year,
                    'posting_period_id' => $postingPeriod->id,
                ]); 
            } else {
                throw new \Exception('Cannot create yearly posting period - previous periods are not properly closed');
            }
        }
        
        return $postingPeriod;
    }

    /**
     * Create Monthly Period for next month
     */
    private function createNextMonthPostingPeriod(PostingPeriod $postingPeriod, Carbon $nextMonth): ?Period
    {
        $startOfNextMonth = $nextMonth->copy()->startOfMonth();
        $endOfNextMonth = $nextMonth->copy()->endOfMonth();
        
        $closedPeriods = $postingPeriod->periods()
            ->where('status', PostingPeriodStatusType::OPEN->value)
            ->where('start_date', '<', $nextMonth->startOfMonth())
            ->update([
                'status' => PostingPeriodStatusType::CLOSED->value,
            ]);

        if (true) {
            $existingPeriod = $postingPeriod->periods()
                ->where('start_date', $startOfNextMonth)
                ->first();
                
            if (!$existingPeriod) {
                $period = $postingPeriod->periods()->create([
                    'start_date' => $startOfNextMonth,
                    'end_date' => $endOfNextMonth,
                    'status' => PostingPeriodStatusType::OPEN,
                ]);
                
                Log::info('Created next month period', [
                    'next_month' => $nextMonth->format('Y-m'),
                    'period_id' => $period->id,
                ]);
                
                return $period;
            } else {
                Log::info('Next month period already exists', [
                    'next_month' => $nextMonth->format('Y-m'),
                    'existing_period_id' => $existingPeriod->id,
                ]);
                
                return $existingPeriod;
            }
        } else {
            throw new \Exception('Cannot create next month period - previous monthly periods are not properly closed');
        }
    }

    /**
     * Legacy method for backward compatibility
     * Redirects to the main invoke method
     */
    public function createPostingPeriod(?Request $request = null): JsonResponse
    {
        return $this->__invoke($request);
    }
}

