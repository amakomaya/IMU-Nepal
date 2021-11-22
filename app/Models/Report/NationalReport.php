<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;

class NationalReport extends Model
{
    protected $connection = 'mysqlreport';

    protected $guarded = [];
}
