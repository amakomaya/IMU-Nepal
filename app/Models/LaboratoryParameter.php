<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboratoryParameter extends Model
{
    protected $table = 'laboratory_parameters';

	protected $fillable = ['id', 'token', 'woman_token', 'day', 'tc', 'dc', 'creatinine', 'alt', 'rbs', 'crp', 'pt', 'd_dimer', 'ldh', 'ferritin', 'blood', 'xray', 'ct', 'checked_by', 'org_code', 'regdev', 'status', 'created_at', 'updated_at', 'checked_by_name'];
}