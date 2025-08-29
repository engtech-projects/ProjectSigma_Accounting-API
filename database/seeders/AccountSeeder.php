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
                    'taxable' => $record['taxable'],
                ]
            );
        }
        $ids = range(311, 388);
        Account::whereIn('id', $ids)->delete();
        
        $taxableAccountNumbers = [
            '200010', '200011', '200030', '200031', '200032', '200033', 
            '200034', '200035', '200036', '200037', '200038', '200039',
        ];

        Account::whereIn('account_number', $taxableAccountNumbers)
        ->update(['taxable' => true]);
    }
}
