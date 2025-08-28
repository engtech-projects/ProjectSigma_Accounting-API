<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class UpdateAccountsTaxableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $taxableAccounts = [
        ['account_number' => '200010', 'taxable' => true],
        ['account_number' => '200011', 'taxable' => true],
        ['account_number' => '200030', 'taxable' => true],
        ['account_number' => '200031', 'taxable' => true],
        ['account_number' => '200032', 'taxable' => true],
        ['account_number' => '200033', 'taxable' => true],
        ['account_number' => '200034', 'taxable' => true],
        ['account_number' => '200035', 'taxable' => true],
        ['account_number' => '200036', 'taxable' => true],
        ['account_number' => '200037', 'taxable' => true],
        ['account_number' => '200038', 'taxable' => true],
        ['account_number' => '200039', 'taxable' => true],
    ];

    foreach ($taxableAccounts as $accountData) {
        $account = Account::where('account_number', $accountData['account_number'])->first();
        
        if ($account) {
            $account->taxable = $accountData['taxable'];
            $account->save();
        }
    }
}
}
