<?php

namespace App\Models\ContentApp;

use Illuminate\Database\Eloquent\Model;

class AppointmentPlan extends Model
{
    protected $table = 'appointment_plan';

    protected $fillable = ['hp_code', 'plan', 'place', 'from', 'to', 'start_time', 'end_time', 'duration', 'total_seat','booked_seat'];


    public function typePlan($type)
    {
    	switch ($type) {
    		case '1':
    			return 'Woman Service';
    		case '2':
    			return 'Immunization Service';
    		default:
                return '';
    	}
    }
}