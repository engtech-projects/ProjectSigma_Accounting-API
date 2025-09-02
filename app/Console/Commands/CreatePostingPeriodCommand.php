<?php

namespace App\Console\Commands;

use App\Services\PostingPeriodService;
use Illuminate\Console\Command;

class CreatePostingPeriodCommand extends Command
{
    protected $signature = 'posting-period:create';

    protected $description = 'Create posting period for the next month';

    public function handle(PostingPeriodService $service): int
    {
        try {
            $service->createPostingPeriod();
            $this->info('Posting period created successfully');
            Log::channel('posting-period')->info('Posting Period Created via Console: ', [
                'fiscal_year_id' => $fiscalYear->id,
                'posting_period_id' => $postingPeriod->id,
                'executed_by' => 'console',
                'timestamp' => now(),
            ]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            report($e);
            $this->error('Failed to create posting period: '.$e->getMessage());
            Log::channel('posting-period')->error('Posting Period Failed to Create via Console: ', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'executed_by' => 'console',
                'timestamp' => now(),
            ]);

            return Command::FAILURE;
        }
    }
}
