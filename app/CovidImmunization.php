<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CovidImmunization extends Model
{
    protected $table = 'covid_immunizations';

    protected $fillable = ['org_code', 'municipality_id', 'data_list', 'expire_date'];
}
