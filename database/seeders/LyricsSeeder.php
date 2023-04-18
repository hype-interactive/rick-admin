<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Lyrics;

class LyricsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lyrics::factory()->count(10)->create();
    }
}
