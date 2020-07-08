<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WomenRequest extends FormRequest
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
        switch ($this->method()) {
           
            case 'POST': {

              return [
                    'name' => 'required|string',
                    'phone' => 'required|string',
                    'age' => 'required|numeric',
                    'lmp_date_en' => 'required|string',
                    'province_id' => 'required|integer',
                    'district_id' => 'required|integer',
                    'municipality_id' => 'required|integer',
                    'tole' => 'required|string',
                    'ward' => 'required|string',
                    'husband_name' => 'required|string',
                    'status' => 'required|numeric',
                    'phone' => 'nullable|regex:/(^([+0-9]+)(\d+)?$)/u|max:15',
                    'username' => 'required|string|unique:users',
                    'password' => 'required|string',
                    're_password' => 'required|same:password',
                    'email'=>'nullable|string|email|'
                ];
            }
            case 'PUT': {
                return [
                    'name' => 'required|string',
                    'phone' => 'required|string',
                    'age' => 'required|numeric',
                    'lmp_date_en' => 'required|string',
                    'province_id' => 'required|integer',
                    'district_id' => 'required|integer',
                    'municipality_id' => 'required|integer',
                    'tole' => 'required|string',
                    'ward' => 'required|string',
                    'husband_name' => 'required|string',
                    'status' => 'required|numeric',
                    'phone' => 'nullable|regex:/(^([+0-9]+)(\d+)?$)/u|max:15',
                    'email'=>'nullable|string|email|'
                ];
            }
            default:break;
        }
        
    }

    public function attributes(){
        return[
            'name' => 'name',
            'phone' => 'phone',
            'age' => 'age',
            'lmp_date_en' => 'lmp date',
            'province_id' => 'province',
            'district_id' => 'district',
            'municipality_id' => 'municipality',
            'tole' => 'tole',
            'ward' => 'ward',
            'husband_name' => 'husband name',
            'status' => 'status',
            'username' => 'Username',
            'password' => 'Password',
            're_password' => 'Confrim Password',
            'email' => 'Email',
        ];
    }
}
