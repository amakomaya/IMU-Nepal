<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;

class HospitalReport extends Model
{
    protected $connection = 'mysqlreport';

    protected $guarded = [];
}
