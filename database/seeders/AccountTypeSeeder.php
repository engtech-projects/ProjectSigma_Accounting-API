<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = database_path('seeders/account_types.csv');
        // Open the CSV file
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            AccountType::updateOrCreate(
                ['id' => $record['id']],
                [
                    'account_type' => $record['account_type'],
                    'account_category' => $record['account_category'],
                    'balance_type' => $record['balance_type'],
                    'notation' => $record['notation'],
                ]
            );
        }
    }
}
