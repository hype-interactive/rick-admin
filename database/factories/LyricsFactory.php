<?php

namespace Database\Factories;

use App\Models\Lyrics;
use Illuminate\Database\Eloquent\Factories\Factory;

class LyricsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lyrics::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'image' => $this->faker->imageUrl(640, 480, 'lyrics', true, 'Faker'),
            'cover_image' => $this->faker->imageUrl(640, 480, 'lyrics', true, 'Faker'),
            'song' => $this->faker->sentence(3),
            'artist_id' => $this->faker->numberBetween(1, 10),
            'album' => $this->faker->sentence(3, true),
            'audio_link' => $this->faker->url,
            'video_link' => $this->faker->url,
            'pin' => rand(0, 1),
            'slug' => $this->faker->slug,
            'visibility' => rand(0, 1),
        ];
    }
}
