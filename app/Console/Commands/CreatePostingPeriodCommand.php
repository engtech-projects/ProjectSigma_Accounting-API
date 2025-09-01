<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\PostingPeriodService;
use App\Models\PostingPeriod;

class CreatePostingPeriodCommand extends Command
{
    protected $signature = 'posting-period:create';
    protected $description = 'Create posting period for the next month';

    public function handle(PostingPeriodService $service): int
    {
        try {
            $result = $service->createPostingPeriod();
            $this->info('Posting period created successfully');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to create posting period: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}