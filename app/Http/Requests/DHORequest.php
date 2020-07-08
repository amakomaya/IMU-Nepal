<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DHORequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {       
        switch ($this->method()) {
             case 'POST': {
                   return [
                     'district_id' => 'required|string|unique:district_infos',
                     'office_address' => 'required|string',
                     'status' => 'required|string',
                     'phone' => 'nullable|regex:/(^([+0-9]+)(\d+)?$)/u|max:15',
                     'username' => 'required|string|unique:users',
                     'password' => 'required|string',
                     're_password' => 'required|same:password',
                     'email'=>'nullable|string|email|'
                 ];
             }
             case 'PUT': {
                 return [
                     'district_id' => 'required|string',
                     'office_address' => 'required|string',
                     'status' => 'required|string',
                     'phone' => 'nullable|regex:/(^([+0-9]+)(\d+)?$)/u|max:15',
                     'email'=>'nullable|string|email|'
                 ];
             }
             default:break;
         }
    }

    public function attributes(){
        return[
            'district_id' => 'district',
            'office_address' => 'office address',
            'status' => 'status',
            'username' => 'username',
            'password' => 'password',
            're_password' => 'confrim Password',
            'email' => 'email',
        ];
    }
}
