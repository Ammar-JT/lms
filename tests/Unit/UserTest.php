<?php

namespace Tests\Unit;

//this comes by default when you make a unit test, i donno why,
//use PHPUnit\Framework\TestCase;
//.. replace it with: 
use Tests\TestCase;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;

class UserTest extends TestCase
{
    use RefreshDatabase;
    public function test_a_user_can_complete_a_lesson(){
        // a customise function to refresh redis db: 
        $this->flushRedis();
        //user
        $user = User::factory()->create();

        //sereis and lesson
        //$series = Series::factory()->create(); <<< /// no need for this, cuz with laravel belongsTo() function
        $lesson = Lesson::factory()->create(); //<<< /// .. laravel will create a series using factory if it didn't find a series.
        
        $lesson2 = Lesson::factory()->create([
            'series_id' => 1
        ]);

        
        //customize function in the model that used redis: 
        $user->completeLesson($lesson);
        $user->completeLesson($lesson2);


        $this->assertEquals(
            Redis::smembers('user:1:series:1'),
            [1,2]
        );

        $this->assertEquals(
            $user->getNumberOfCompletedLessonsForASeries($lesson->series), 2
        );


        //redis->user:12:sertiees:12=> [1]
    }


    public function test_can_get_percentage_completed_for_series_for_a_user(){
        //same steps of the first method:
        $this->flushRedis();
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $lesson2 = Lesson::factory()->create([
            'series_id' => 1
        ]);

        //but here we want 2 more lessons, but belong to the same series which is series 1: 
        Lesson::factory()->create(['series_id' => 1]);
        Lesson::factory()->create(['series_id' => 1]);

        //


        $user->completeLesson($lesson);
        $user->completeLesson($lesson2);

        //assert
        $this->assertEquals(
            $user->percentageCompletedForSeries($lesson->series), 50
        );
    }

    public function test_can_know_if_a_user_has_started_a_series(){



        $this->flushRedis();
        $user = User::factory()->create();

        //2 lesson belong to series 1 
        $lesson = Lesson::factory()->create();
        $lesson2 = Lesson::factory()->create([
            'series_id' => 1
        ]);

        // more lesson, but belong to series 2
        $lesson3 = Lesson::factory()->create();


        //user watches a lesson in the 1st series
        $user->completeLesson($lesson2);

        //assert that returns true hasStartedSeries(1)
        $this->assertTrue($user->hasStartedSeries($lesson->series));

        //assert that returns false hasStartedSeries(2)
        $this->assertFalse($user->hasStartedSeries($lesson3->series));  


    }

    public function test_can_get_completed_lessons_for_a_series(){
        $this->flushRedis();
        $user = User::factory()->create();

        //3 lesson belong to series 1 
        $lesson = Lesson::factory()->create();
        $lesson2 = Lesson::factory()->create(['series_id' => 1]);
        $lesson3 = Lesson::factory()->create(['series_id' => 1]);


        //completed some lessons in the series
        $user->completeLesson($lesson);
        $user->completeLesson($lesson2);



        //get completed lessons method
        $completedLessons = $user->getCompletedLessons($lesson->series);

        //assert this $completedLessons is an instance of collection (type collection)
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $completedLessons);

        //assert that >> random() << (random() get any elemnt from the collection randomly)
        //.. will return an object from the type lesson (instance of Lesson)
        $this->assertInstanceOf(Lesson::class, $completedLessons->random());


        //now we get the collection of lessons object, we want the ids (so dump): 
        $completedLessonsIds = $completedLessons->pluck('id')->all();



        $this->assertTrue(in_array( $lesson->id, $completedLessonsIds));
        $this->assertTrue(in_array( $lesson2->id, $completedLessonsIds));
        $this->assertFalse(in_array( $lesson3->id, $completedLessonsIds));



    }


    public function test_can_check_if_user_has_completed_lesson(){
            $this->flushRedis();
            //user 
            $user = User::factory()->create();

            // series with its lessons 
            $lesson = Lesson::factory()->create();
            $lesson2 = Lesson::factory()->create(['series_id' => 1]);


            //complete a lesson
            $user->completeLesson($lesson);

            //assert true,
            $this->assertTrue($user->hasCompletedLesson($lesson));
            $this->assertFalse($user->hasCompletedLesson($lesson2));


    }

    public function test_can_get_all_series_being_watched_by_user(){

        $this->flushRedis();

        //user 
        $user = User::factory()->create();

        //series with its lessons 
        $lesson = Lesson::factory()->create();
        $lesson2 = Lesson::factory()->create(['series_id' => 1]);
        $lesson3 = Lesson::factory()->create();
        $lesson4 = Lesson::factory()->create(['series_id' => 2]);
        $lesson5 = Lesson::factory()->create();
        $lesson6 = Lesson::factory()->create(['series_id' => 3]);

        //complete lesson 1,2:
        $user->completeLesson($lesson);
        $user->completeLesson($lesson3);

        $startedSeries = $user->seriesBeingWatched();
        //collection of series
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $startedSeries);
        $this->assertInstanceOf(Series::class, $startedSeries->random());

        $idsOfStartedSeries = $startedSeries->pluck('id')->all();
        $this->assertTrue(
            in_array($lesson->series->id, $idsOfStartedSeries)
        );

        $this->assertTrue(
            in_array($lesson3->series->id, $idsOfStartedSeries)
        );

        $this->assertFalse(
            in_array($lesson5->series->id, $idsOfStartedSeries)
        );


        //assertions





    }


    public function test_can_get_number_of_completed_lesson_for_a_user(){
        $this->withoutExceptionHandling();
        $this->flushRedis();

        //user 
        $user = User::factory()->create();

        //series with its lessons 
        $lesson = Lesson::factory()->create();
        $lesson2 = Lesson::factory()->create(['series_id' => 1]);
        $lesson3 = Lesson::factory()->create();
        $lesson4 = Lesson::factory()->create(['series_id' => 2]);
        $lesson5 = Lesson::factory()->create(['series_id' => 2]);

        //action
        $user->completeLesson($lesson);
        $user->completeLesson($lesson3);
        $user->completeLesson($lesson5);
        

        
        //assertion
        $this->assertEquals(3, $user->getTotalNumberOfCompletedLessons());

    }

    public function test_can_get_lesson_to_be_watched_by_user(){
        $this->flushRedis();

        //user 
        $user = User::factory()->create();

        //series with its lessons 
        $lesson = Lesson::factory()->create([ 'episode_number' => 100]);
        $lesson2 = Lesson::factory()->create(['series_id' => 1, 'episode_number' => 200]);
        $lesson3 = Lesson::factory()->create(['series_id' => 1, 'episode_number' => 300]);
        $lesson4 = Lesson::factory()->create(['series_id' => 1, 'episode_number' => 400]);

        //action
        $user->completeLesson($lesson);
        $user->completeLesson($lesson2);

        //assertion
        $nextLesson = $user->getNextLessonToWatch($lesson->series);
        $this->assertEquals($lesson3->id, $nextLesson->id);

        //action
        $user->completeLesson($lesson3);

        //assertion
        $this->assertEquals($lesson4->id, $user->getNextLessonToWatch($lesson->series)->id);


    }

}
