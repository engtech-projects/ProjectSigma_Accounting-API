<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the CSV file
        $file = database_path('seeders/accounts.csv');

        // Open the CSV file
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0); // Use the first row as the header

        foreach ($csv as $record) {
            Account::updateOrCreate(
                ['id' => $record['id']], // Prevent duplicate records
                [
                    'account_type_id' => $record['account_type_id'],
                    'account_number' => $record['account_number'],
                    'account_name' => $record['account_name'],
                    'account_description' => $record['account_description'],
                    'bank_reconciliation' => $record['bank_reconciliation'],
                    'is_active' => $record['is_active'],
                    'statement' => $record['statement'],
                ]
            );
        }
        $ids = range(311, 388);
        Account::whereIn('id', $ids)->delete();
    }
}
