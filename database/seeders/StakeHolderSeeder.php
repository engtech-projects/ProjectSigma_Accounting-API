<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stakeholder;

class StakeholderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Stakeholder::create(['name' => 'Maybank']);
		Stakeholder::create(['name' => 'DYSEKCO ENTERPRISES CORPORATION']);
		Stakeholder::create(['name' => '22NH0031-MEJVEC']);
    }
}
