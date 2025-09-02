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

            return Command::SUCCESS;
        } catch (\Exception $e) {
            report($e);
            $this->error('Failed to create posting period: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
