<?php

namespace App\Console\Commands;

use App\Services\FiscalYearService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateFiscalYearCommand extends Command
{
    protected $signature = 'fiscal-year:create {--year= : Specific year to create fiscal year for}';
    protected $description = 'Create fiscal year for the next year or specified year (automatically closes open fiscal years if needed)';
    public function handle(FiscalYearService $service): int
    {
        try {
            $year = $this->option('year');
            if ($year) {
                $this->info("Creating fiscal year for {$year}...");
                $fiscalYear = $service->create([
                    'period_start' => "{$year}-01-01",
                    'period_end' => "{$year}-12-31",
                    'status' => 'open'
                ]);
            } else {
                $this->info('Creating fiscal year for next year...');
                $fiscalYear = $service->createNextYearFiscalYear();
            }
            $this->info('Fiscal year created successfully');
            $this->table(
                ['ID', 'Period Start', 'Period End', 'Status'],
                [[$fiscalYear->id, $fiscalYear->period_start, $fiscalYear->period_end, $fiscalYear->status]]
            );
            Log::channel('fiscal-year')->info('Fiscal Year Created via Console', [
                'fiscal_year_id' => $fiscalYear->id,
                'period_start' => $fiscalYear->period_start,
                'period_end' => $fiscalYear->period_end,
                'timestamp' => now(),
            ]);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            report($e);
            $this->error('Failed to create fiscal year: ' . $e->getMessage());
            Log::channel('fiscal-year')->error('Fiscal Year Failed to Create via Console', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now(),
            ]);
            return Command::FAILURE;
        }
    }
}
