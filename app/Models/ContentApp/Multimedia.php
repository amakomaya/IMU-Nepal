<?php

namespace App\Models\ContentApp;

use Illuminate\Database\Eloquent\Model;

class Multimedia extends Model
{
    protected $table = 'multimedia';

    protected $fillable = ['type', 'title_en', 'title_np','description_en', 'description_np','week_id','status','thumbnail', 'path' ];


	public function getWeekNumberAttribute(){
	    return "{$this->week_id} weeks";
	} 

	public function getTypeAttribute(){
	    switch ("{$this->attributes['type']}") {
	    	case 1:
	    		return 'Video';
	    		break;
	    	case 2:
	    		return 'Audio';
	    		break;
	    	case 3:
	    		return 'Text';
	    		break;
	    	default:
	    		return 'N\\A';
	    		break;
	    }
	}

	public function scopeType($query, $type){
	    return $query->where('type', $type);
	}
}
