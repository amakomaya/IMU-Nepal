<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZeroReport extends Model
{
    protected $fillable = [
        'org_code', 'type', 'province_id', 'district_id', 'municipality_id', 'date', 'status'
    ];
}
