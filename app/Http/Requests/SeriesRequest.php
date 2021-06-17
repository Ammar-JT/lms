<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class SeriesRequest extends FormRequest
{
    public function uploadSeriesImage(){
        $this->fileName = Str::slug($this->title) .'.'. $this->file('image')->getClientOriginalExtension();
        
        $image = $this->file('image')->storePubliclyAs(
            'public/series', $this->fileName
        );

        return $this;
    }
}
