<?php

namespace App\Http\Requests;

use App\Models\Series;
use Illuminate\Support\Str;

class CreateSeriesRequest extends SeriesRequest
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
    
    public function storeSeries(){
        $series = Series::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'image_url' => 'series/' . $this->fileName
        ]);


        session()->flash('success', 'Series created successfully.');

        //redirect user to a the created series page:
        return redirect()->route('series.show', $series->slug);  
    }
    
}
