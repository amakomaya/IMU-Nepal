<?php

namespace App\Models\Woman;

use Illuminate\Database\Eloquent\Model;
use App\Models\District;
use App\Models\Municipality;


class WomanRegisterApp extends Model
{
    protected $table = 'woman_registration';

    protected $fillable = ['token','name','age','is_first_time_parent', 'register_as', 'lmp_date_en','lmp_date_np','phone','email','district_id','municipality_id','ward_no','tole','username','password','longitude','latitude', 'status', 'mis_data', 'created_at','updated_at'];

    protected $dates = ['created_at', 'updated_at'];

    public function district()
    {
		return $this->belongsTo(District::class);
	}

	public function municipality()
    {
		return $this->belongsTo(Municipality::class);
    }  
    
    public function scopeExclude($query,$value = array()) 
    {
        return $query->select( array_diff( $this->fillable,(array) $value) );
    }
}
