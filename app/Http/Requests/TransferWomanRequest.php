<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferWomanRequest extends FormRequest
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
        'province_id' => 'required|string',
        'district_id' => 'required|string',
        'municipality_id' => 'required|string',
        'org_code' => 'required|string',
        'message' => 'required|string',
        ];
    }
        
    public function attributes(){
        return[
            'province_id' => 'province',
            'district_id' => 'district',
            'municipality_id' => 'municipality',
            'org_code' => 'healthpost',
            'message' => 'message',
        ];
    }
}
