<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MunicipalityRequest extends FormRequest
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
                        'phone' => 'required|string',
                        'province_id' => 'required|string',
                        'district_id' => 'required|string',
                        'municipality_id' => 'required|string|unique:municipality_infos',
                        'office_address' => 'required|string',
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
                    'phone' => 'required|string',
                    'province_id' => 'required|string',
                    'district_id' => 'required|string',
                    'municipality_id' => 'required|string',
                    'office_address' => 'required|string',
                    'status' => 'required|numeric',
                    'phone' => 'nullable|regex:/(^([+0-9]+)(\d+)?$)/u|max:15',
                    'phone' => 'nullable|regex:/(^([+0-9]+)(\d+)?$)/u|max:15',
                    'email'=>'nullable|string|email|'
                ];
            }
            default:break;
        }
        
    }

    public function attributes(){
        return[
            'phone' => 'phone',
            'province_id' => 'province',
            'district_id' => 'district',
            'municipality_id' => 'municipality',
            'office_address' => 'office address',
            'status' => 'status',
            'username' => 'Username',
            'password' => 'Password',
            're_password' => 'Confrim Password',
            'email' => 'Email',
        ];
    }
}
