<?php

namespace App\Http\Requests\ContentApp\Advertisement;

use Illuminate\Foundation\Http\FormRequest;

class PostAdvertisementRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {       
        return [
            'type' => 'required',
            'url' => 'required',
            'plan' => 'required',
            'expire_date' => 'required',
        ];
    }

    public function attributes(){
        return [
           
            'expire_date' => 'Expire date'
        ];
    }

    public function messages(){
        return [
           
            'required' => 'The :attributes is requires'
        ];
    }
}