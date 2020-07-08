<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public static function modelDistrictInfo($token){
		$model = DistrictInfo::where('token', $token)->get()->first();
		return $model;
	}

	public static function provinceIdByDistrictId($districtId){
		$district = District::where('id', $districtId)->get()->first();
		return $district->province_id;
	} 
}
