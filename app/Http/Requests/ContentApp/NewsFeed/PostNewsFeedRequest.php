<?php

namespace App\Http\Requests\ContentApp\NewsFeed;

use Illuminate\Foundation\Http\FormRequest;

class PostNewsFeedRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {       
        return [
            'author' => 'required'
        ];
    }

    public function attributes(){
        return[
            'author' => 'author'
        ];
    }
}