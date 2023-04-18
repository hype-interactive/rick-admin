<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Video;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Video::factory()->count(10)->create();
    }
}
