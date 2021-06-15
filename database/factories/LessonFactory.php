<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\Series;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lesson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'series_id' => function(){
                return Series::factory()->create()->id;
            },
            'episode_number' => 100,
            'video_id' => '545098743'

        ];
    }
}
