<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WardRequest extends FormRequest
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
        //switch ($this->method()){ 
        //case 'POST': {}
        //}

        switch ($this->method()) {
             case 'POST': {
                   return [
                    'ward_no' => 'required|integer',
                    'phone' => 'required|string',
                    'province_id' => 'required|string',
                    'district_id' => 'required|string',
                    'municipality_id' => 'required|string',
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
                    'ward_no' => 'required|integer',
                    'phone' => 'required|string',
                    'province_id' => 'required|string',
                    'district_id' => 'required|string',
                    'municipality_id' => 'required|string',
                    'office_address' => 'required|string',
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
            'ward_no' => 'ward no',
            'token' => 'token',
            'phone' => 'phone',
            'province_id' => 'province id',
            'district_id' => 'district id',
            'municipality_id' => 'municipality id',
            'office_address' => 'office address',
            'status' => 'status',
            'username' => 'username',
            'password' => 'password',
            're_password' => 'confrim Password',
            'email' => 'email',
        ];
    }
}
