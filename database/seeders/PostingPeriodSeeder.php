<?php

namespace Database\Seeders;

use App\Models\PostingPeriod;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PostingPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentDate = Carbon::now();

        $postingPeriod = PostingPeriod::updateOrCreate([
            'period_start' => $currentDate->copy()->startOfYear(),
            'period_end' => $currentDate->copy()->endOfYear(),
        ]);

        for ($month = 1; $month <= 12; $month++) {

            $startOfMonth = Carbon::createFromDate($currentDate->year, $month, 1);
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

            $postingPeriod->periods()->updateOrCreate([

                'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
            ]);
        }

    }
}
