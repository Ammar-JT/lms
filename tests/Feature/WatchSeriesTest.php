<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WatchSeriesTest extends TestCase
{
    use RefreshDatabase;
    public function test_a_user_can_complete_a_series(){
        $this->withoutExceptionHandling();
        //flushRedis: 
        $this->flushRedis();


        //user
        $user = User::factory()->create();
        //login the user
        $this->actingAs($user);


        //series and 2 lessons
        $lesson = Lesson::factory()->create();
        $lesson2 = Lesson::factory()->create(['series_id' => 1]);

        //post -> complete lesson
        $response = $this->post("/series/complete-lesson/{$lesson->id}");

        //assertions: 
        $response->assertStatus(200); 
        $response->assertJson([
            'status' => 'ok'
        ]);

        $this->assertTrue(
            $user->hasCompletedLesson($lesson)
        );

        $this->assertFalse(
            $user->hasCompletedLesson($lesson2)
        );
    }
}
