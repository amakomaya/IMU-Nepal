<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class province extends Model
{  
	public static function modelProvinceInfo($token){
		$model = ProvinceInfo::where('token', $token)->get()->first();
		return $model;
	}

	public static function districtList($provicneId){
		$districts = District::where('province_id', $provicneId)->get();
		$list = array();
		if(count($districts)>0){
			foreach ($districts as $district) {
				$list[] = $district->id;
			}
		}
		return $list;
	}
}
