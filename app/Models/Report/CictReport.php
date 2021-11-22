<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;

class CictReport extends Model
{
    protected $connection = 'mysqlreport';

    protected $guarded = [];
}
