<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvinceRequest extends FormRequest
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
                    'province_id' => 'required|string|unique:province_infos',
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
                    'province_id' => 'required|string',
                    'office_address' => 'required|string',
                    'status' => 'required|string',
                    'email'=>'nullable|string|email|'
                ];
            }
            default:break;
        }
        
    }

    public function attributes(){
        return[
            'province_id' => 'Province',
            'office_address' => 'office address',
            'status' => 'status',
            'username' => 'username',
            'password' => 'password',
            're_password' => 'confrim password',
            'email' => 'email',
        ];
    }
}
