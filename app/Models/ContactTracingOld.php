<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactTracingOld extends Model
{
    protected $connection = 'mysqldump';

    protected $table='contact_tracing';
    protected $fillable = [
        'token', 'case_token', 'case_id', 'name', 'caste',
        'gender', 'age', 'age_unit', 'case_relation', 'case_meet', 
        'case_meet_specific', 'case_meet_date', 'contact_classification', 
        'address', 'phone', 'checked_by', 'org_code', 'registered_device', 'status', 
        'created_at', 'updated_at', 'checked_by_name'
    ];

    public function contactDetail()
    {
        return $this->hasOne('App\Models\ContactDetailOld', 'contact_tracing_token', 'token');
    }

    public function contactFollowUp()
    {
        return $this->hasMany('App\Models\ContactFollowUpOld', 'contact_token', 'token');
    }
}
