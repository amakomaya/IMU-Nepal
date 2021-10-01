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
        $hospital_type = $request['hospital_type']??null;
        if($hospital_type) {
          if(is_array($hospital_type)) {
            if($hp_code!=""){
              return [$hp_code];
            }elseif($municipality_id!=""){
                return Organization::where('municipality_id', $municipality_id)->whereIn('hospital_type', $hospital_type)->pluck('hp_code');
            }elseif($district_id!=""){
                return Organization::where('district_id', $district_id)->whereIn('hospital_type', $hospital_type)->pluck('hp_code');
            }elseif($province_id!=""){
                return Organization::where('province_id', $province_id)->whereIn('hospital_type', $hospital_type)->pluck('hp_code');
            }
            return Organization::whereIn('hospital_type', $hospital_type)->pluck('hp_code');
          } else {
            if($hp_code!=""){
              return [$hp_code];
            }elseif($municipality_id!=""){
                return Organization::where('municipality_id', $municipality_id)->where('hospital_type', $hospital_type)->pluck('hp_code');
            }elseif($district_id!=""){
                return Organization::where('district_id', $district_id)->where('hospital_type', $hospital_type)->pluck('hp_code');
            }elseif($province_id!=""){
                return Organization::where('province_id', $province_id)->where('hospital_type', $hospital_type)->pluck('hp_code');
            }
            return Organization::where('hospital_type', $hospital_type)->pluck('hp_code');
          }
          
        } else {
          if($hp_code!=""){
            return [$hp_code];
          }elseif($municipality_id!=""){
              return Organization::where('municipality_id', $municipality_id)->pluck('hp_code');
          }elseif($district_id!=""){
              return Organization::where('district_id', $district_id)->pluck('hp_code');
          }elseif($province_id!=""){
              return Organization::where('province_id', $province_id)->pluck('hp_code');
          }
          return Organization::pluck('hp_code');
        }
	}
}