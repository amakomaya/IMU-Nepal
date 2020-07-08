<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutReachClinic extends Model
{
    protected $fillable = ['name','province_id','district_id','municipality_id','ward_no','hp_code','address','phone','longitude','lattitude','status','updated_at'];

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

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
