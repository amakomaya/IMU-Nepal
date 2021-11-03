<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class OrganizationMember extends Model
{
	use LogsActivity;

    protected $table='organization_members';


    protected static $logFillable = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "OrganizationMember model has been {$eventName}";
    }

    protected static $logName = 'healthworker';

    protected static $logOnlyDirty = true;

    protected $appends = ['lab_report_count'];

    protected $fillable = ['token','name','province_id','district_id','municipality_id','ward','org_code','image','post','phone','tole','registered_device','role','longitude','latitude','status','updated_at'];

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

	public static function getHealthpost($org_code)
    {
    	$healthpost = Organization::where('org_code',$org_code)->get()->first();
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
		$healthworker = OrganizationMember::where('token', $token)->get()->first();
		if(count($healthworker)>0){
			return $healthworker->name;
		}
	}

	public static function findHealthPhoneByToken($token){
		$healthworker = OrganizationMember::where('token', $token)->get()->first();
		if(count($healthworker)>0){
			return $healthworker->phone;
		}
	}

	public static function findHealthWorkerPostByToken($token){
		$healthworker = OrganizationMember::where('token', $token)->get()->first();
		if(count($healthworker)>0){
			return $healthworker->post;
		}
	}

	public static function findHealthWorkerSignature($token){
		$healthworker = OrganizationMember::where('token', $token)->get()->first();
		if(count($healthworker)>0){
			if($healthworker->image!="")
				echo "<img src=\"". \Illuminate\Support\Facades\Storage::url('health-worker/'.$healthworker->image)."\"  width=\"80\"/>";
		}
	}

	public static function checkValidId($id){
		$loggedInToken = Auth::user()->token;
		// $loggedInHpCode = Organization::modelHealthpost($loggedInToken)->org_code;
		$recoredHealthworker = OrganizationMember::where('id',$id)->get()->first();
		if(count($recoredHealthworker)>0){
			$recoredHpCode = $recoredHealthworker->org_code;
			// if($loggedInHpCode==$recoredHpCode){
				return true;
			// }
		}
		return false;
	}

    public function user(){
        return $this->belongsTo('App\User', 'token', 'token');
    }

    public function getLabReportCountAttribute()
    {
        return \App\Models\LabTest::where('org_code', $this->org_code)->active()->count();
    }
}
