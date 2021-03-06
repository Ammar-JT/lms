<?php

namespace App\Http\Requests;

use App\Models\Series;
use Illuminate\Support\Str;

class UpdateSeriesRequest extends SeriesRequest
{
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
        ];
    }


    public function updateSeries(Series $series){

        if($this->hasFile('image')){
            $series->image_url = 'series/' . $this->uploadSeriesImage()->fileName;
        }
        $series->title = $this->title;
        $series->description = $this->description;
        $series->slug = Str::slug($this->title);

        $series->save();

        session()->flash('success', 'Series updated successfully.');
    }


    

}
