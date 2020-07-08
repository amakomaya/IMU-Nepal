<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class MunicipalityInfo extends Model
{
	use LogsActivity;

    protected static $logFillable = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Municipality model has been {$eventName}";
    }

    protected static $logName = 'municipality';

	protected static $logOnlyDirty = true;
	
    protected $fillable = ['token','phone','province_id','district_id','municipality_id','office_address','office_longitude','office_lattitude','status','updated_at'];

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
}
