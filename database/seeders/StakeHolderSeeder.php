<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StakeHolder;

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
