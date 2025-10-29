<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AccountTypeSeeder::class,
            AccountSeeder::class,
            AccountGroupSeeder::class,
            BookSeeder::class,
            // PostingPeriodSeeder::class,
            // StakeHolderSeeder::class,
            TermsSeeder::class,
            ReportGroupSeeder::class,
            TransactionFlowModelSeeder::class,
        ]);
    }
}
