<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalParameter extends Model
{
    
	protected $table = 'clinical_parameters';

	protected $fillable = ['token', 'woman_token', 'day', 'temperature', 'pulse', 'bp', 'respiratory', 'spo2', 'chest_crepts', 'checked_by', 'hp_code', 'regdev', 'status', 'created_at', 'updated_at', 'checked_by_name'];

}
