<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Auth;
use App\Models\ProvinceInfo;
use App\Models\DistrictInfo;
use App\Models\MunicipalityInfo;
use App\Models\Ward;
use App\Models\Healthpost;
use App\Models\HealthWorker;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use Notifiable, LogsActivity;

    protected static $ignoreChangedAttributes = ['remember_token','updated_at'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "User model has been {$eventName}";
    }

    protected static $logName = 'user';

    protected static $logAttributes = ['username', 'email','role', 'password'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email','role', 'password','token','imei'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token','role'
    ];

    public static function getUserId($token){
        $user = User::where('token',$token)->get()->first();
        if(count($user)>0)
            return $user->id;
    }

    public static function getFirstLoggedInRole($token){
        $user = User::where('token',$token)->get()->first();
        if(count($user)>0){
            $role = $user->role;
            $role = str_replace("_", " ", $role);
            $role = ucwords($role);
            return $role;
        }
    }

    public static function checkAuthForCenter(){
        if(Auth::user()->role!="main"){
            return false;
        }
        return true;
    }

    public static function checkAuthForIndexShowProvince(){
        if(Auth::user()->role!="main" && Auth::user()->role!="center"){
            return false;
        }
        return true;
    }

    public static function checkAuthForIndexShowDho(){
        if(Auth::user()->role!="province"){
            return false;
        }
        return true;
    }

    public static function checkAuthForIndexShowMunicipality(){
        if(Auth::user()->role!="dho"){
            return false;
        }
        return true;
    }

    public static function checkAuthForShowWard(){
        if(Auth::user()->role!="dho"){
            return false;
        }
        return true;
    }

    public static function checkAuthForShowHealthpost(){
        if(Auth::user()->role!="dho"){
            return false;
        }
        return true;
    }

    public static function checkAuthForCreateUpdateDelProvince(){
        if(Auth::user()->role!="main"){
            return false;
        }
        return true;
    }


    public static function checkAuthForViewByMain(){
        if(Auth::user()->role!="main"){
            return false;
        }
        return true;
    }

    public static function checkAuthForViewByMunicipality(){
        if(Auth::user()->role!="municipality"){
            return false;
        }
        return true;
    }

    public static function checkAuthForIndexShowWard(){
        if(Auth::user()->role=="healthpost" && Auth::user()->role=="healthworker"){
            return false;
        }
    }

    public static function checkAuthForCreateUpdateDelWard(){
        if(Auth::user()->role!="municipality"){
            return false;
        }
    }

    public static function checkAuthForViewByWard(){
        if(Auth::user()->role!="ward"){
            return false;
        }
        return true;
    }

    public static function checkAuthForIndexShowHealthpost(){
        if(Auth::user()->role=="healthworker"){
            return false;
        }
    }

    public static function checkAuthForCreateUpdateDelHealthpost(){
        if(Auth::user()->role!="municipality"){
            return false;
        }
    }

    public static function checkAuthForViewByHealthpost(){
        if(Auth::user()->role!="healthpost"){
            return false;
        }
        return true;
    }

    public static function checkAuthForViewByHealthpostHealworker(){
        if(Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker"){
            return false;
        }
        return true;
    }

    public static function checkAuthForCreateUpdateDelHealthworker(){
        if(Auth::user()->role!="healthpost"){
            return false;
        }
        return true;
    }

    public static function getAppRoleOnly(){
        $role = Auth::user()->role;  
        $token = Auth::user()->token;
        if($role=="healthworker"){
            $healthworker = HealthWorker::where('token', $token)->get()->first();
            $name = "(".$healthworker->name.")";
            $role = $healthworker->role;
            if($role=="doctor"){
                $role = "healthworker";
            }
        } 
        $role = str_replace("_", " ", $role);
        $role = ucwords($role);

        return $role;
    }

    public static function getAppRole(){
        $role = Auth::user()->role;
        $token = Auth::user()->token;
        $name = "";

        if($role=="province"){
            $province = ProvinceInfo::select('provinces.province_name as name')
                ->join('provinces','provinces.id','=','province_infos.province_id')
                ->where('province_infos.token', $token)
                ->get()
                ->first();
            $name = "(".$province->name.")";
        }

        if($role=="dho"){
            $district = DistrictInfo::select('districts.district_name as name')
                ->join('districts','districts.id','=','district_infos.district_id')
                ->where('district_infos.token', $token)
                ->get()
                ->first();
            $name = "(".$district->name.")";
        }

        if($role=="municipality"){
            $municipality = MunicipalityInfo::select('municipalities.municipality_name as name')
                ->join('municipalities','municipalities.id','=','municipality_infos.municipality_id')
                ->where('municipality_infos.token', $token)
                ->get()
                ->first();
            $name = "(".$municipality->name.")";
        }   


        if($role=="ward"){
            $ward = Ward::select('wards.ward_no', 'municipalities.municipality_name')
                ->join('municipalities','municipalities.id','=','wards.municipality_id')
                ->where('wards.token', $token)
                ->get()
                ->first();
            $name = "(".$ward->municipality_name." Ward No. ".$ward->ward_no.")";
        }


        if($role=="healthpost"){
            $healthpost = Healthpost::where('token', $token)->get()->first();
            $name = "(".$healthpost->name.")";
        }

        if($role=="healthworker"){
            $healthworker = HealthWorker::where('token', $token)->get()->first();
            $name = "(".$healthworker->name.")";
            $role = $healthworker->role;
            if($role=="doctor"){
                $role = "healthworker";
            }
        }

        $name = ucwords($name)." ";
        $role = str_replace("_", " ", $role);
        $role = ucwords($role);
        return $role." Admin ".$name;
    }

    public function scopeGetUserIdByToken($query, $token)
    {
        return $query->where('token', $token)->get();
    }
}