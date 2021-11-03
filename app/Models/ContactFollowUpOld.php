<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactFollowUpOld extends Model
{
    protected $connection = 'mysqldump';

    protected $table='contact_follow_up';
    protected $fillable = [
        'token', 'contact_token', 'contact_with_case_day', 'follow_up_day',
        'follow_up_date', 'symptoms', 'symptoms_other', 'checked_by', 'org_code', 
        'registered_device', 'sync', 'update_status', 'status', 'created_at', 'updated_at', 
        'checked_by_name'
    ];
}
