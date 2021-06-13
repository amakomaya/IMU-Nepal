<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Province extends Model
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
    public static function getProvince($id)
    {
        $province = province::where('id',$id)->get()->first();
        return $province->province_name ?? '';
    }
}
