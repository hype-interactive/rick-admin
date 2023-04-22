<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'url' => $this->faker->url,
            'slug' => $this->faker->slug(3, false),
            'visibility' => rand(0, 1),
            'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
