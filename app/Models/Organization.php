<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Organization extends Model
{
	use LogsActivity;

    protected static $logFillable = true;

    protected $table='healthposts';


    public function getDescriptionForEvent(string $eventName): string
    {
        return "Organization model has been {$eventName}";
    }

    protected static $logName = 'healthpost';

    protected static $logOnlyDirty = true;

    protected $fillable = ['name','token','hmis_uid','province_id','district_id','municipality_id','hp_code','ward_no','phone','address','longitude','lattitude','status','created_at','updated_at'];

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

    public static function getHealthpost($hp_code)
    {
    	$healthpost = Organization::where('hp_code',$hp_code)->get()->first();
    	if(count($healthpost)>0){
    		return $healthpost->name;
    	}
    }

	public static function getUserId($token){
		$user = User::where('token', $token)->get()->first();
		if(count($user)>0){
    		return $user->id;
		}
	}

	public static function generateHpCode($hp){
		$title = substr($hp, 0, 3);
		$random = rand(0, 1000);
		echo $hpCode = $title.$random;
		$healthpost = Organization::where('hp_code',$hpCode)->get()->first();
    	if(count($healthpost)>0){
    		return self::generateHpCode($hp);
    	}else{
    		return $hpCode;
    	}
	}

	public static function isHpCodeAlreadyExist($hpCode){
		$healthpost = Organization::where('hp_code',$hpCode)->get()->first();
    	if(count($healthpost)>0){
    		return true;
    	}else{
    		return false;
    	}
	}

    public static function getHpCodeWoman(){
        $token = Auth::user()->token;
        $role = Auth::user()->role;
        if($role=="healthpost"){
            $healthpost = Organization::where('token',$token)->get()->first();
            return $healthpost->hp_code;
        }elseif($role=="healthworker"){
            $healthworker = OrganizationMember::where('token', $token)->get()->first();
            return $healthworker->hp_code;
        }
    }

    public static function checkValidId($id){
        $loggedInToken = Auth::user()->token;
        $loggedInWardId = Ward::modelWard($loggedInToken)->id;
        $recoredWardNo = Organization::where('id',$id)->get()->first()->ward_no;
        $recoredMunicipalityId = Organization::where('id',$id)->get()->first()->municipality_id;
        $recordedWardId = Ward::where([['municipality_id', $recoredMunicipalityId],['ward_no',$recoredWardNo]])->get()->first()->id;

        if($loggedInWardId==$recordedWardId){
            return true;
        }
        return false;
    }

    public static function modelHealthpost($token){
        $model = Organization::where('token', $token)->get()->first();
        return $model;
	}
	
	public function getDistrictName($token)
    {
		return  District::where('id',$token)->first()->district_name;
	}

	public function getRegisters($hp_code)
	{
		return SuspectedCase::where('hp_code', $hp_code)->active()->count();
	}

	public function getSampleCollection($hp_code)
	{
		return \App\Models\SampleCollection::where('hp_code', $hp_code)->active()->count();
	}
    public function user(){
        return $this->belongsTo('App\User', 'token', 'token');
    }
}
