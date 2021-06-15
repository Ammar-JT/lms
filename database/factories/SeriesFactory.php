<?php

namespace Database\Factories;

use App\Models\Series;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Series::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(5);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'image_url' => $this->faker->imageUrl(),
            'description' => $this->faker->paragraph()
        ];
    }
}
