<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Series;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateLessonsTest extends TestCase
{
    use RefreshDatabase;

    private function loginAdmin(){
        $user = User::factory()->create();
        Config::push('lms.administrators', $user->email);
        $this->actingAs($user);
    }



    public function test_a_user_can_create_lessons(){

        $this->withoutExceptionHandling();

        //lets loggin as admin first: 
        $this->loginAdmin();
        
        //create series: 
        $series = Series::factory()->create();

        //array of the new lesson
        $lesson = [
            'title' => 'new lesson',
            'description' => 'new lesson description',
            'episode_number' => 23,
            'video_id' => 23423
        ];

        //Ajax post request, like the axios in vue.js: 
        $this->postJson("/admin/$series->id/lessons", $lesson)
             ->assertStatus(201) // 200 which means the request is successful, but 201 means the request let to create something successfuly, so use 201 cuz it's the new create successfully responses
             ->assertJson($lesson); //response will come back with json values of $lesson, so this assertJson($lesson) function compare the received values with the $lesson values
        
        //this literally check the db lessons table if it has this record
        $this->assertDatabaseHas('lessons', [
            'title' => $lesson['title']
        ]);

    }

    public function test_a_title_is_required_to_create_a_lesson(){
        //$this->withoutExceptionHandling();

        //lets loggin as admin first: 
        $this->loginAdmin();
        
        //create series: 
        $series = Series::factory()->create();

        //array of the new lesson
        $lesson = [
            'description' => 'new lesson description',
            'episode_number' => 23,
            'video_id' => 23423
        ];

        //Ajax post request, like the axios in vue.js: 
        $this->postJson("/admin/$series->id/lessons", $lesson)
             ->assertStatus(422);
    }


    public function test_a_description_is_required_to_create_a_lesson(){
        //$this->withoutExceptionHandling();

        //lets loggin as admin first: 
        $this->loginAdmin();
        
        //create series: 
        $series = Series::factory()->create();

        //array of the new lesson
        $lesson = [
            'description' => 'new lesson description',
            'episode_number' => 23,
            'video_id' => 23423
        ];

        //Ajax post request, like the axios in vue.js: 
        $this->postJson("/admin/$series->id/lessons", $lesson)
             ->assertStatus(422);
    }

    public function test_an_episode_number_is_required_to_create_a_lesson(){
        //$this->withoutExceptionHandling();

        //lets loggin as admin first: 
        $this->loginAdmin();
        
        //create series: 
        $series = Series::factory()->create();

        //array of the new lesson
        $lesson = [
            'title' => 'title',
            'description' => 'new lesson description',
            'video_id' => 23423
        ];

        //Ajax post request, like the axios in vue.js: 
        $this->postJson("/admin/$series->id/lessons", $lesson)
             ->assertStatus(422);
    }



    public function test_a_video_id_is_required_to_create_a_lesson(){
        //$this->withoutExceptionHandling();

        //lets loggin as admin first: 
        $this->loginAdmin();
        
        //create series: 
        $series = Series::factory()->create();

        //array of the new lesson
        $lesson = [
            'title' => 'title',
            'description' => 'new lesson description',
            'episode_number' => 23,
        ];

        //Ajax post request, like the axios in vue.js: 
        $this->post("/admin/$series->id/lessons", $lesson)
             ->assertSessionHasErrors('video_id');
    }





    
}
