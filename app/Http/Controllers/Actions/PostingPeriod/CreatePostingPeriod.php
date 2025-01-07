<?php

use App\Http\Controllers\Controller;
use App\Models\PostingPeriod;
use Carbon\Carbon;

class CreatePostingPeriod extends Controller
{
    public function __invoke(array $data)
    {
        $currentDate = Carbon::now();

        $postingPeriod = PostingPeriod::orderBy('period_end', 'desc')->first();

        if (!$postingPeriod || $currentDate > $postingPeriod->period_end) {
            $postingPeriod = PostingPeriod::create([
                'period_start' => $currentDate->copy()->startOfYear(),
                'period_end' => $currentDate->copy()->endOfYear()->addYear(),
            ]);
        }

        $lastPeriod = $postingPeriod->periods()->orderBy('end_date', 'desc')->first();

        if (!$lastPeriod || $currentDate > $lastPeriod->end_date) {
            $startMonth = $lastPeriod ? $lastPeriod->end_date->addMonth()->month : 1;
            $endMonth = 12;
            $year = $lastPeriod ? $lastPeriod->end_date->addMonth()->year : $currentDate->year;

            for ($month = $startMonth; $month <= $endMonth; $month++) {
                $startOfMonth = Carbon::create($year, $month, 1);
                $endOfMonth = $startOfMonth->copy()->endOfMonth();

                if ($postingPeriod->periods()->where('start_date', $startOfMonth)->exists()) {
                    continue;
                }

                $postingPeriod->periods()->create([
                    'start_date' => $startOfMonth,
                    'end_date' => $endOfMonth,
                ]);

                if ($endOfMonth >= $postingPeriod->period_end) {
                    break;
                }
            }
        }
    }
}
