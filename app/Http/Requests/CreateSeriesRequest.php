<?php

namespace App\Http\Requests;

use App\Models\Series;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class CreateSeriesRequest extends FormRequest
{
    protected $fileName;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image'

        ];
    }
    
    public function uploadSeriesImage(){
        $this->fileName = Str::slug($this->title) .'.'. $this->file('image')->getClientOriginalExtension();
        $image = $this->file('image')->storePubliclyAs('series', $this->fileName);

        return $this;
    }


    public function storeSeries(){
        Series::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'image_url' => 'series/' . $this->fileName
        ]);
    }
    
}
