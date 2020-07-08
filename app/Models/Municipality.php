<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    public static function modelMunicipalityInfo($token){
		$model = MunicipalityInfo::where('token', $token)->get()->first();
		return $model;
	}

	public static function getMunicipality($id)
    {
    	$municipality = \App\Models\Municipality::where('id',$id)->get()->first();
    		return $municipality->municipality_name ?? '';
    }
}