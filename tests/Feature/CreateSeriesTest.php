<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateSeriesTest extends TestCase
{
    //this run migration, and after the testing finish it roll it back
    //.. also it run it in sqlite, which is separate one than your mysql!
    //.. how? you set up that in 'phpunit.xml'
    use RefreshDatabase;


    public function test_a_user_can_create_a_series(){
        //do the test without handling the exception, so we can see the errors in the cli: 
        $this->withoutExceptionHandling();

        //this create a fake storage file
                    //(and this put the file in the disk you specify which is 'local' not 's3')
        Storage::fake(config('filesystems.default'));

        $this->post('/admin/series', [
            'title' => 'vue.js for the best',
            'description' => 'the best vue casts ever',
            'image' => UploadedFile::fake()->image('image-series.png')
        ])->assertRedirect()
        ->assertSessionHas('success', 'Series created successfully.');

        //this check if the filed is uploaded to our fake storage
        Storage::disk(config('filesystems.default'))->assertExists(
            'series/' . Str::slug('vue.js for the best') . '.png'
        );

        //this assert that our sqlite db has the column for the slug
        //.. cuz we store the slug in the controller not here!
        $this->assertDatabaseHas('series', [
            'slug' => Str::slug('vue.js for the best')
        ]);

    }

    public function test_a_series_must_be_created_with_a_title(){
        //$this->withoutExceptionHandling();


        $this->post('/admin/series', [
            'description' => 'the best vue casts ever',
            'image' => UploadedFile::fake()->image('image-series.png')
        ])->assertSessionHasErrors('title');
    }

    public function test_a_series_must_be_created_with_a_description(){
        //$this->withoutExceptionHandling();


        $this->post('/admin/series', [
            'title' => 'vue.js for the best',
            'image' => UploadedFile::fake()->image('image-series.png')
        ])->assertSessionHasErrors('description');
    }

    public function test_a_series_must_be_created_with_an_image(){
        //$this->withoutExceptionHandling();


        $this->post('/admin/series', [
            'title' => 'vue.js for the best',
            'description' => 'the best vue casts ever',
        ])->assertSessionHasErrors('image');
    }

    public function test_a_series_must_be_created_with_an_image_not_string(){
        //$this->withoutExceptionHandling();


        $this->post('/admin/series', [
            'title' => 'vue.js for the best',
            'description' => 'the best vue casts ever',
            'image' => 'string image'
        ])->assertSessionHasErrors('image');
    }
}
