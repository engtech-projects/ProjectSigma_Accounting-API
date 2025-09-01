<?php
namespace App\Http\Controllers;

use App\Services\PostingPeriodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CreatePostingPeriod extends Controller
{
    private $postingPeriodService;

    public function __construct(PostingPeriodService $postingPeriodService)
    {
        $this->postingPeriodService = $postingPeriodService;
    }

    /**
     * Handle the incoming request.
     * Create posting period for the NEXT month
     */
    public function __invoke(): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->postingPeriodService->ensureCurrentMonthAndClosePrevious();
            $currentDate = Carbon::now();
            $nextMonth = $currentDate->copy()->addMonth();
            $fiscalYear = $this->postingPeriodService->ensureFiscalYear($nextMonth);
            $postingPeriod = $this->postingPeriodService->createPostingPeriod();

            DB::commit();

            Log::info('Posting Period Created via API: ', [
                'fiscal_year_id' => $fiscalYear->id,
                'posting_period_id' => $postingPeriod->id,
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'Posting Period Successfully Created',
                'data' => [
                    'fiscal_year' => $fiscalYear,
                    'posting_period' => $postingPeriod,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Posting Period Failed to Create via API: ', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Posting Period Failed to Create: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Legacy method for backward compatibility
     * Redirects to the main invoke method
     */
    public function createPostingPeriod(): JsonResponse
    {
        return $this->__invoke();
    }
}
