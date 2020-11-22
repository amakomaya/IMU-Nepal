<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseManagement extends Model
{
    protected $table='case_mgmt';
    protected $fillable = [
        'token', 'woman_token', 'contact_with_covid_place', 'contact_travel', 'name',
        'relation', 'last_meet_date', 'covid_infect_place', 'case_gone_festival',
        'case_gone_festival_info', 'case_contact_same_illness',
        'case_contact_same_illness_info', 'case_gone_institution',
        'case_gone_institution_info', 'case_additional_info', 'checked_by',
        'hp_code', 'regdev', 'sync', 'update_status', 'status', 'created_at',
        'updated_at', 'checked_by_name', 'reference_date_from', 'reference_date_to'
    ];
}
