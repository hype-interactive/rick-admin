<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'subtitle' => $this->faker->paragraph(2),
            'user_id' => rand(1, 10),
            'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'content' => $this->faker->paragraph(10),
            'image' => $this->faker->imageUrl(),
            'slug' => $this->faker->slug(3, false),
            'category_id' => rand(1, 10),
            'visibility' => rand(0, 1),
            'pin' => rand(0, 1),
        ];
    }
}
