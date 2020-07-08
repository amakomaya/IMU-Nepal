<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\District;

class DistrictInfo extends Model
{
    protected $fillable = ['district_id','token','phone','office_address','office_longitude','office_lattitude','status','updated_at'];
    
        public function district()
        {
            return $this->belongsTo(District::class);
        }
    
        public static function getUserId($token){
            $user = User::where('token', $token)->get()->first();
            if(count($user)>0){
                return $user->id;
            }
        }
}
