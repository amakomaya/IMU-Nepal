<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class HealthWorker extends Model
{
	use LogsActivity;

    protected static $logFillable = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "HealthWorker model has been {$eventName}";
    }

    protected static $logName = 'healthworker';

    protected static $logOnlyDirty = true;

    protected $fillable = ['token','name','province_id','district_id','municipality_id','ward','hp_code','image','post','phone','tole','registered_device','role','longitude','latitude','status','updated_at'];

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

    public static function getDistrictName($district_id){
        try{
            return District::where('id', $district_id)->first()->district_name;
        }catch (\Exception $exception){
            return "";
        }
    }

	public static function findHealthWorkerByToken($token){
		$healthworker = HealthWorker::where('token', $token)->get()->first();
		if(count($healthworker)>0){
			return $healthworker->name;
		}
	}

	public static function findHealthPhoneByToken($token){
		$healthworker = HealthWorker::where('token', $token)->get()->first();
		if(count($healthworker)>0){
			return $healthworker->phone;
		}
	}

	public static function findHealthWorkerPostByToken($token){
		$healthworker = HealthWorker::where('token', $token)->get()->first();
		if(count($healthworker)>0){
			return $healthworker->post;
		}
	}

	public static function findHealthWorkerSignature($token){
		$healthworker = HealthWorker::where('token', $token)->get()->first();
		if(count($healthworker)>0){
			if($healthworker->image!="")
				echo "<img src=\"". \Illuminate\Support\Facades\Storage::url('health-worker/'.$healthworker->image)."\"  width=\"80\"/>";
		}
	}

	public static function checkValidId($id){
		$loggedInToken = Auth::user()->token;
		// $loggedInHpCode = Healthpost::modelHealthpost($loggedInToken)->hp_code;
		$recoredHealthworker = HealthWorker::where('id',$id)->get()->first();
		if(count($recoredHealthworker)>0){
			$recoredHpCode = $recoredHealthworker->hp_code;
			// if($loggedInHpCode==$recoredHpCode){
				return true;
			// }
		}
		return false;
	}
	
}
