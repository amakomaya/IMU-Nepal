<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Ward extends Model
{
    protected $fillable = ['ward_no','token','phone','province_id','district_id','municipality_id','office_address','office_longitude','office_lattitude','status','updated_at'];

	public function province()
    {
		return $this->belongsTo(Province::class);
	}

	public function district()
    {
		return $this->belongsTo(District::class);
	}

	public function municipality()
    {
		return $this->belongsTo(Municipality::class);
	}

	public static function getWardNo($wardId){
		$ward = Ward::find($wardId);
		if(count($ward)>0)
			return $ward->ward_no;
	}

	public static function checkValidId($id){
		$loggedInMunicipalityId = "";
		$recoredMunicipalityId = "";
		$loggedInToken = Auth::user()->token;
		$municipality = MunicipalityInfo::where('token', $loggedInToken)->get()->first();
		if(count($municipality)>0){		
			$loggedInMunicipalityId = $municipality->municipality_id;
		}

		$wards = Ward::where('id',$id)->get()->first();
		if(count($wards)>0){
			$recoredMunicipalityId = $wards->municipality_id;
		}		

		if($loggedInMunicipalityId==$recoredMunicipalityId){
			return true;
		}
		return false;
	}

	public static function modelWard($token){
		$model = Ward::where('token', $token)->get()->first();
		return $model;
	}
}
