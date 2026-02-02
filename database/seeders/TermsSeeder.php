<?php

namespace Database\Seeders;

use App\Enums\MainModuleType;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Term::updateOrCreate([
            'name' => 'SALARY_AND_WAGES_BASIC_PAY',
            'description' => 'Basic salary and wages',
            'account_id' => 89,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'SALARY_AND_WAGES_BASIC_PAY_OFFICE',
            'description' => 'Basic salary and wages for office staff',
            'account_id' => 90,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'SALARY_AND_WAGES_OT_PAY',
            'description' => 'Basic salary and wages with overtime pay',
            'account_id' => 91,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'SALARY_AND_WAGES_OT_PAY_OFFICE',
            'description' => 'Basic salary and wages with overtime pay for office staff',
            'account_id' => 92,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'SALARY_AND_WAGES_HOLIDAY_PAY',
            'description' => 'Basic salary and wages with holiday pay',
            'account_id' => 93,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'SALARY_AND_WAGES_HOLIDAY_PAY_OFFICE',
            'description' => 'Basic salary and wages with holiday pay for office staff',
            'account_id' => 94,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'OVERTIME_PAY',
            'description' => 'Basic salary and wages with overtime pay',
            'account_id' => 95,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Term::updateOrCreate([
            'name' => 'CASH_IN_BANK_MAYBANK',
            'description' => 'Cash in bank - Maybank account',
            'account_id' => 6,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Term::updateOrCreate([
            'name' => 'SSS_PREMIUM_PAYABLE',
            'description' => 'SSS Premium Payable',
            'account_id' => 268,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'SSS_PREMIUM_PAYABLE_OFFICE',
            'description' => 'SSS Premium Payable (Office)',
            'account_id' => 269,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'SSS_SALARY_CALAMITY_LOAN_PAY',
            'description' => 'SSS Salary/Calamity Loan Pay',
            'account_id' => 270,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'HDMF_PREMIUM_PAYABLE',
            'description' => 'HDMF Premium Payable',
            'account_id' => 271,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'HDMF_PREMIUM_PAYABLE_OFFICE',
            'description' => 'HDMF Premium Payable (Office)',
            'account_id' => 272,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'HDMF_SALARY_CALAMITY_LOAN_PAY',
            'description' => 'HDMF Salary/Calamity Loan Pay',
            'account_id' => 273,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'HDMF_MODIFIED_PAG_IBIG_2',
            'description' => 'HDMF Modified Pag-ibig 2',
            'account_id' => 274,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'PHIC_PREMIUM_PAYABLE',
            'description' => 'PHIC Premium Payable',
            'account_id' => 275,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Term::updateOrCreate([
            'name' => 'PHIC_PREMIUM_PAYABLE_OFFICE',
            'description' => 'PHIC Premium Payable (Office)',
            'account_id' => 276,
            'type' => MainModuleType::HRMS->value,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
