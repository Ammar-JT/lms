<?php

namespace Tests\Unit;

use Tests\TestCase;
//use PHPUnit\Framework\TestCase;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LessonTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_can_get_next_and_previous_lessons_from_a_lesson(){
        //make lessons and their series: 
        $lesson = Lesson::factory()->create(['episode_number' => 200]);
        $lesson2 = Lesson::factory()->create(['series_id' => $lesson->series_id,'episode_number' => 100]);
        $lesson3 = Lesson::factory()->create(['series_id' => $lesson->series_id,'episode_number' => 300]);


  
        //assertion
        $this->assertEquals($lesson->getNextLesson()->id, $lesson3->id);
        $this->assertEquals($lesson3->getPrevLesson()->id, $lesson->id);
        $this->assertEquals($lesson->getPrevLesson()->id, $lesson2->id);

        $this->assertNull($lesson2->getPrevLesson());
        $this->assertNull($lesson3->getNextLesson());


    }
}
