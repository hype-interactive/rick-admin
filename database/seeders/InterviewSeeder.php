<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Interview;

class InterviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Interview::factory()->count(10)->create();
    }
}
