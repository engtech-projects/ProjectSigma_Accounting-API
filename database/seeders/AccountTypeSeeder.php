<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AccountType;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader; 
use Carbon\Carbon;

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
                    'account_category'  => $record['account_category'],
                    'balance_type' => $record['balance_type'],
                    'notation' => $record['notation']
                ]
            );
        }
    }
}
