<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactTracing extends Model
{
    protected $table='contact_tracing';
    protected $fillable = [
        'token', 'woman_token', 'case_id', 'name', 'caste',
        'gender', 'age', 'age_unit', 'case_relation', 'case_meet', 
        'case_meet_specific', 'case_meet_date', 'contact_classification', 
        'address', 'phone', 'checked_by', 'org_code', 'regdev', 'status', 
        'created_at', 'updated_at', 'checked_by_name'
    ];

    public function contactDetail()
    {
        return $this->hasOne('App\Models\ContactDetail', 'contact_tracing_token', 'token');
    }

    public function contactFollowUp()
    {
        return $this->hasMany('App\Models\ContactFollowUp', 'contact_token', 'token');
    }
}
