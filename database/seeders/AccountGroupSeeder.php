<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Database\Seeder;

class AccountGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disbursementAccounts = Account::whereHas('accountType', function ($query) {
            $query->where('account_category', 'expenses');
        })->get();

        $cashAccounts = Account::whereHas('accountType', function ($query) {
            $query->whereIn('account_category', ['asset', 'income', 'revenue']);
        })->get();

        $disbursement = AccountGroup::updateOrCreate(['name' => 'disbursement']);
        $cash = AccountGroup::updateOrCreate(['name' => 'cash']);

        foreach ($disbursementAccounts as $dv) {
            $disbursement->accounts()->sync($dv->id, false);
        }

        foreach ($cashAccounts as $cv) {
            $cash->accounts()->sync($cv->id, false);
        }
    }
}
