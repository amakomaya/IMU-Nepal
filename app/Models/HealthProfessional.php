<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthProfessional extends Model
{
    protected $table = 'health_professional';

    protected $fillable = [
        'id', 'token', 'organization_type', 'organization_name', 'organization_phn', 'organization_address', 'designation',
        'level', 'service_date', 'name', 'gender', 'age', 'phone',
        'province_id', 'district_id', 'municipality_id', 'tole', 'ward', 'perm_province_id',
        'perm_district_id', 'perm_municipality_id', 'perm_tole', 'perm_ward', 'citizenship_no', 'issue_district',
        'allergies', 'disease', 'covid_status', 'status','created_at',
        'updated_at'
    ];
}
