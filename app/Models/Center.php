<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    protected $fillable = ['name','token','phone','office_address','office_longitude','office_lattitude','status','updated_at'];


    public static function getUserId($token){
		$user = User::where('token', $token)->get()->first();
		if(count($user)>0){
    		return $user->id;
		}
	}
}