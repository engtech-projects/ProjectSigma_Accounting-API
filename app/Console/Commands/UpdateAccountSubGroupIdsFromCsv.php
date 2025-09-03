<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateAccountSubgroupIdsFromCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:account-subgroups {csv_file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates account table sub_group_id from a CSV file.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $csvFile = $this->argument('csv_file');

        if (! Storage::exists($csvFile)) {
            $this->error("CSV file '$csvFile' not found.");

            return 1;
        }

        $filePath = Storage::path($csvFile);
        $file = fopen($filePath, 'r');

        if ($file === false) {
            $this->error('Failed to open CSV file.');

            return 1;
        }

        $header = fgetcsv($file);

        if ($header === false || $header[0] !== 'account title' || $header[1] !== 'sub group') {
            $this->error("CSV file must have 'account title' and 'sub group' columns.");
            fclose($file);

            return 1;
        }

        try {
            DB::beginTransaction();

            $existingSubgroups = DB::table('sub_groups')->pluck('id', 'name')->toArray();

            while (($row = fgetcsv($file)) !== false) {
                $accountTitle = $row[0];
                $subgroupName = strtoupper($row[1]); // Capitalize the subgroup name

                if (isset($existingSubgroups[$subgroupName])) {
                    $subgroupId = $existingSubgroups[$subgroupName];

                    DB::table('accounts')
                        ->where('account_name', $accountTitle)
                        ->update(['sub_group_id' => $subgroupId]);
                } else {
                    $this->error("Subgroup '$subgroupName' not found in sub_groups table for account '$accountTitle'.");
                    DB::rollBack();
                    fclose($file);

                    return 1;
                }
            }

            DB::commit();
            $this->info('Account sub_group_ids updated successfully.');
            fclose($file);

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: '.$e->getMessage());
            fclose($file);

            return 1;
        }
    }
}
