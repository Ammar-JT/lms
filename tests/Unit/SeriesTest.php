<?php

namespace Tests\Unit;

use Tests\TestCase;

//this comes by default when you make a unit test, i donno why,
//use PHPUnit\Framework\TestCase;
//.. replace it with: 
use App\Models\Lesson;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;


class SeriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_series_can_get_image_path(){

        $series = Series::factory()->create([
            'image_url' => 'series/series-slug.png'
        ]);
        //you use: 
        //          $series->image_path 
        //..instead of 
        //          $series->getImagePathAttribute()
        //cuz this is a laravel accessor, and laravel make you call it as an column
        $imagePath = $series->image_path;

        //instead of testing the path:
        //$this->assertEquals('storage/series/series-slug.png', $imagePath);
        //.. you can test the url: 
        $this->assertEquals(asset('storage/series/series-slug.png'), $imagePath);

    }
    
    public function test_can_get_ordered_lessons_for_a_series(){
        
        //make lessons and their series: 
        $lesson = Lesson::factory()->create(['episode_number' => 200]);

        $lesson2 = Lesson::factory()->create(['series_id' => $lesson->series_id,'episode_number' => 100]);

        $lesson3 = Lesson::factory()->create(['series_id' => $lesson->series_id,'episode_number' => 300]);


        //call the getOrderedLessons 
        $lessons = $lesson->series->getOrderedLessons();

        //make sure that the lessons are in the correct order: 
        $this->assertInstanceOf(Lesson::class, $lessons->random());

        $this->assertEquals($lessons->first()->id, $lesson2->id);

        $this->assertEquals($lessons->last()->id,  $lesson3->id);
    }
}
