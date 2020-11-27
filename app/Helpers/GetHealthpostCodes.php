<?php

namespace App\Helpers;
use App\Models\Organization;
use Auth;

class GetHealthpostCodes
{
	public static function filter($request)
	{
		$province_id = $request['province_id']; 
        $district_id = $request['district_id']; 
        $municipality_id = $request['municipality_id']; 
        $hp_code = $request['hp_code'];

        if($hp_code!=""){
            return [$hp_code];
        }elseif($municipality_id!=""){
            return Organization::where('municipality_id', $municipality_id)->get('hp_code')->toArray();
        }elseif($district_id!=""){
            return Organization::where('district_id', $district_id)->get('hp_code')->toArray();
        }elseif($province_id!=""){
            return Organization::where('province_id', $province_id)->get('hp_code')->toArray();
        }
            return Organization::get('hp_code')->toArray();
	}
}