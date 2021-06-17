<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSeriesRequest extends FormRequest
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


    public function uploadSeriesImage(){
        $this->fileName = Str::slug($this->title) .'.'. $this->file('image')->getClientOriginalExtension();
        $image = $this->file('image')->storePubliclyAs('series', $this->fileName);

        return $this;
    }

}
