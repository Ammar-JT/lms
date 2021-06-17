<?php

namespace Tests\Unit;

use App\Models\Series;

//this comes by default when you make a unit test, i donno why,
//use PHPUnit\Framework\TestCase;
//.. replace it with: 
use Tests\TestCase;


class SeriesTest extends TestCase
{

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
}
