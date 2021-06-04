<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class provinceInfo extends Model
{
    protected $fillable = ['permission_id','province_id','token','phone','office_address','office_longitude','office_lattitude','status','updated_at'];

	public function province()
    {
		return $this->belongsTo(Province::class);
	}

	public static function getUserId($token){
		$user = User::where('token', $token)->get()->first();
		if(count($user)>0){
    		return $user->id;
		}
	}


	// permission_id
    public function getPermissionToString($id){
	    switch ($id){
            case 1:
                return "all";
            default:
                return "view-only";
        }
    }
}
