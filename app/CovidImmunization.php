<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CovidImmunization extends Model
{
    protected $table = 'covid_immunizations';

    protected $fillable = ['hp_code', 'municipality_id', 'data_list', 'expire_date'];
}
