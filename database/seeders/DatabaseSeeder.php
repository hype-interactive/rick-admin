<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            AuthorDetailsSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            ArticleSeeder::class,
            ArticleTagSeeder::class,
            VideoSeeder::class,
            InterviewSeeder::class,
            ArtistSeeder::class,
            LyricsSeeder::class,
            // AdvertSeeder::class,
            LogSeeder::class,
        ]);
    }
}
