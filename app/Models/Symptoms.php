<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptoms extends Model
{
    
    protected $table = 'symptoms';

	protected $fillable = ['token', 'woman_token', 'day', 'fever', 'cough', 'chills', 'fatigue', 'muscle_pain', 'headache', 'loss_of_smell', 'loss_of_taste', 'diarrhea', 'running_nose', 'sore_throat', 'sob', 'others', 'checked_by', 'hp_code', 'regdev', 'status', 'created_at', 'updated_at', 'checked_by_name'];
}
