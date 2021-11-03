<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HealthWorkerRequest extends FormRequest
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
                        'province_id' => 'required|integer',
                        'district_id' => 'required|integer',
                        'municipality_id' => 'required|integer',
                        'org_code' => 'required|string',
                        'post' => 'required',
                        'tole' => 'required|string',
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
                    'province_id' => 'required|integer',
                    'district_id' => 'required|integer',
                    'municipality_id' => 'required|integer',
                    'post' => 'required',
                    'org_code' => 'required|string',
                    'tole' => 'required|string',
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
            'province_id' => 'province id',
            'district_id' => 'district id',
            'municipality_id' => 'municipality id',
            'org_code' => 'hp code',
            'tole' => 'tole',
            'role' => 'role',
            'status' => 'status',
            'username' => 'Username',
            'password' => 'Password',
            're_password' => 'Confrim Password',
            'email' => 'Email',
        ];
    }
}
