<?php

namespace Database\Factories;

use App\Models\Interview;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Interview::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'subtitle' => $this->faker->sentence(3),
            'body' => $this->faker->paragraph(4),
            'video_id' => rand(1, 10),
            'slug' => $this->faker->slug(3, false),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'visibility' => rand(0, 1),
            'pin' => rand(0, 1),
        ];
    }
}
