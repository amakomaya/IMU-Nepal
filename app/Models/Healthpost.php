<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Healthpost extends Model
{
	use LogsActivity;

    protected static $logFillable = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Healthpost model has been {$eventName}";
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
    	$healthpost = Healthpost::where('hp_code',$hp_code)->get()->first();
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
		$healthpost = Healthpost::where('hp_code',$hpCode)->get()->first();
    	if(count($healthpost)>0){
    		return self::generateHpCode($hp);
    	}else{
    		return $hpCode;
    	}
	}

	public static function isHpCodeAlreadyExist($hpCode){
		$healthpost = Healthpost::where('hp_code',$hpCode)->get()->first();
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
            $healthpost = Healthpost::where('token',$token)->get()->first();
            return $healthpost->hp_code;
        }elseif($role=="healthworker"){
            $healthworker = HealthWorker::where('token', $token)->get()->first();
            return $healthworker->hp_code;
        }
    }

    public static function checkValidId($id){
        $loggedInToken = Auth::user()->token;
        $loggedInWardId = Ward::modelWard($loggedInToken)->id;
        $recoredWardNo = Healthpost::where('id',$id)->get()->first()->ward_no;
        $recoredMunicipalityId = Healthpost::where('id',$id)->get()->first()->municipality_id;
        $recordedWardId = Ward::where([['municipality_id', $recoredMunicipalityId],['ward_no',$recoredWardNo]])->get()->first()->id;

        if($loggedInWardId==$recordedWardId){
            return true;
        }
        return false;
    }

    public static function modelHealthpost($token){
        $model = Healthpost::where('token', $token)->get()->first();
        return $model;
	}
	
	public function getDistrictName($token)
    {
		return  District::where('id',$token)->first()->district_name;
	}

	public function getAmcRecords($hp_code)
	{
		return Woman::where('hp_code', $hp_code)->active()->count();
	}

	public function getVtcRecords($hp_code)
	{
		return BabyDetail::where('hp_code', $hp_code)->active()->count();
	}
}
