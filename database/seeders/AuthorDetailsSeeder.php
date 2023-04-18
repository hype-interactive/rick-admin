<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuthorDetails;

class AuthorDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AuthorDetails::factory()->count(10)->create();
    }
}
