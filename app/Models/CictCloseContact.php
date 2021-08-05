<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CictCloseContact extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'cict_id', 'case_id', 'parent_case_id', 'hp_code', 'checked_by', 
        'name', 'age', 'age_unit', 'sex',
        'phone', 'relationship', 'relationship_others', 'contact_type'
    ];

	public function parentCase()
    {
		return $this->belongsTo('App\Models\CictTracing', 'parent_case_id', 'case_id');
	}

	public function contact()
    {
		return $this->hasOne('App\Models\CictContact', 'case_id', 'case_id');
	}

	public function followUp()
    {
		return $this->hasOne('App\Models\CictFollowUp', 'case_id', 'case_id');
	}

}
