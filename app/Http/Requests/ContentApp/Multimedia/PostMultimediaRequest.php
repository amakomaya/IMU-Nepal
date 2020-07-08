<?php

namespace App\Http\Requests\ContentApp\Multimedia;

use Illuminate\Foundation\Http\FormRequest;

class PostMultimediaRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {       
        return [
            'type' => 'required',
            'title_en' => 'required',
            'title_np' => 'required',
            'week_id' => 'required',
            'status' => 'required'
        ];
    }

    public function attributes(){
        return[
            'title_en' => 'English Title',
            'title_np' => 'Nepali Title',
            'week_id' => 'week number',
            'status' => 'required'
        ];
    }
}