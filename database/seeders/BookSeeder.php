<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Book::updateOrCreate([
            'name' => 'disbursement',
            'code' => 'DV',
            'account_group_id' => 1,
        ]);

        Book::updateOrCreate([
            'name' => 'cash',
            'code' => 'CV',
            'account_group_id' => 2,
        ]);
    }
}
