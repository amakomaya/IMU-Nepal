<?php

namespace App\Models\ContentApp;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';

    protected $fillable = ['data', 'completed_at', 'note'];

	public function typePurpose($type)
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