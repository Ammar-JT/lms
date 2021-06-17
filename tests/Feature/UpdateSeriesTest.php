<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Series;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateSeriesTest extends TestCase
{

    private function loginAdmin(){
        $user = User::factory()->create();
        Config::push('lms.administrators', $user->email);
        $this->actingAs($user);
    }
    
    public function test_a_user_can_update_a_series(){
        $this->withoutExceptionHandling();
        //login as admin
        $this->loginAdmin();

        //assert storage image
        //(and this put the file in the disk you specify which is 'local' not 's3')
        Storage::fake(config('filesystems.default'));         //this create a fake storage file

        //create series using factory so we can update it: 
        $series = Series::factory()->create();

        //put request
        $this->put(route('series.update', $series->slug), [
            'title' => 'updated to new title',
            'description' => 'updated updated updated',
            'image' => UploadedFile::fake()->image('updated-series-image.png')
        ])->assertRedirect(route('series.index'))
        ->assertSessionHas('success', 'Series updated successfully.');

        //this check if the filed is uploaded to our fake storage
        Storage::disk(config('filesystems.default'))->assertExists(
            'public/series/' . Str::slug('updated to new title') . '.png'
        );

        //this assert that our sqlite db has the column for the slug
        //.. cuz we store the slug in the controller not here!
        $this->assertDatabaseHas('series', [
            'slug' => Str::slug('updated to new title'),
            'image_url' => 'series/updated-to-new-title.png'
        ]);

        //assert that db has a particular
    }

    public function test_an_image_is_not_required_to_update_a_series(){
        $this->withoutExceptionHandling();
        $this->loginAdmin();
        Storage::fake(config('filesystems.default'));         //this create a fake storage file
        $series = Series::factory()->create();

        $this->put(route('series.update', $series->slug), [
            'title' => 'updated to new title',
            'description' => 'updated updated updated',
        ])->assertRedirect(route('series.index'))
        ->assertSessionHas('success', 'Series updated successfully.');


        // NOTICE: assertMissing() instead of assertExists()
        Storage::disk(config('filesystems.default'))->assertMissing(
            'public/series/' . Str::slug('updated to new title') . '.png'
        );

        //this assert that our sqlite db has the column for the slug
        //.. cuz we store the slug in the controller not here!
        $this->assertDatabaseHas('series', [
            'slug' => Str::slug('updated to new title'),
            'image_url' => $series->image_url
        ]);

        //assert that db has a particular
    }
}
