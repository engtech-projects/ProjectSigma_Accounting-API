<?php

namespace Database\Seeders;

use App\Models\PostingPeriod;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostingPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $postingPeriod = [
            "period_start" => Carbon::now(),
            "period_end" => Carbon::now(),
        ];

        PostingPeriod::create($postingPeriod);
    }
}
