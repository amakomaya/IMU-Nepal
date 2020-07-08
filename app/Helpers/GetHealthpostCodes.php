<?php

namespace App\Helpers;
use App\Models\Healthpost;
use Auth;

class GetHealthpostCodes
{
	public static function filter($request)
	{
		$province_id = $request['province_id']; 
        $district_id = $request['district_id']; 
        $municipality_id = $request['municipality_id']; 
        $ward_no = $request['ward_id'];
        $hp_code = $request['hp_code'];

        if($hp_code!=""){
            return [$hp_code];
        }elseif($ward_no!=""){
            return ['ward_no'=>$ward_no, 'municipality_id'=>$municipality_id];
        }elseif($municipality_id!=""){
            return Healthpost::where('municipality_id', $municipality_id)->get('hp_code')->toArray();
        }elseif($district_id!=""){
            return Healthpost::where('district_id', $district_id)->get('hp_code')->toArray();
        }elseif($province_id!=""){
            return Healthpost::where('province_id', $province_id)->get('hp_code')->toArray();
        }
            return Healthpost::get('hp_code')->toArray();
	}
}