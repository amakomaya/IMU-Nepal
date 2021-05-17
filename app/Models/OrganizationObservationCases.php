<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationObservationCases extends Model
{
    protected $fillable = ['hp_code','add','transfer_to_bed','return_to_home'];
}
