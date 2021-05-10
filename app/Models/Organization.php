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

    protected $fillable = ['name','token','hmis_uid','province_id',
        'district_id','municipality_id','hp_code','ward_no','phone',
        'no_of_beds', 'no_of_ventilators','vaccination_center_id', 'no_of_icu', 'hospital_type',
        'address','longitude','lattitude','status','created_at','updated_at' , 'daily_consumption_of_oxygen', 'is_oxygen_facility', 'no_of_hdu'
    ];

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

	public function hospitalType($type){
		$list = [1=>'HOME Isolation & CICT',5=>'Institutional Isolation',2=>'Lab Test Only', 3=>'Lab & Treatment', 4=>'Normal'];
		return $list[$type];
    }

	public static function isHpCodeAlreadyExist($hpCode){
		$healthpost = Organization::where('hp_code',$hpCode)->get()->first();
    	if(count($healthpost)>0){
    		return true;
    	}else{
    		return false;
    	}
	}

	public static function modelHealthpost($token){
        $model = Organization::where('token', $token)->get()->first();
        return $model;
	}

    public function user(){
        return $this->belongsTo('App\User', 'token', 'token');
    }
}
