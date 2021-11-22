<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;

class PoeReport extends Model
{
    protected $connection = 'mysqlreport';

    protected $guarded = [];
}
