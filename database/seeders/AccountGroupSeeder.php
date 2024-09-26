<?php

namespace Database\Seeders;

use App\Models\AccountGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accountGroupSeed = [
            [
                'account_group_name' => 'Journal'
            ],
            [
                'account_group_name' => 'Payroll'
            ],
        ];

        foreach ($accountGroupSeed as $accountGroup) {
            AccountGroup::create([
                'account_group_name' => $accountGroup['account_group_name']
            ]);
        }
    }
}