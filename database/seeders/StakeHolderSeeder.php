<?php

namespace Database\Seeders;

use App\Models\StakeHolder;
use Illuminate\Database\Seeder;

class StakeHolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StakeHolder::updateOrCreate(['name' => 'Maybank']);
        StakeHolder::updateOrCreate(['name' => 'DYSEKCO ENTERPRISES CORPORATION']);
        StakeHolder::updateOrCreate(['name' => '22NH0031-MEJVEC']);
    }
}
