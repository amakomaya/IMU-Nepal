<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SymptomsOld extends Model
{
    protected $connection = 'mysqldump';
    
    protected $table = 'symptoms';

	protected $fillable = ['token', 'woman_token', 'day', 'fever', 'cough', 'chills', 'fatigue', 'muscle_pain', 'headache', 'loss_of_smell', 'loss_of_taste', 'diarrhea', 'running_nose', 'sore_throat', 'sob', 'others', 'checked_by', 'org_code', 'registered_device', 'status', 'created_at', 'updated_at', 'checked_by_name'];
}
