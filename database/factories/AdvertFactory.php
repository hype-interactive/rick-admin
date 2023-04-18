<?php

namespace Database\Factories;

use App\Models\Advert;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Advert::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'type' => $this->faker->randomElement(['vertical', 'horizontal']),
            'link' => $this->faker->url,
            'price' => $this->faker->randomFloat(2, 100, 10000),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
